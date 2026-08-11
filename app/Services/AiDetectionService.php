<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiDetectionService
{
    private $gptzeroApiKey;
    private $gptzeroUrl = 'https://api.gptzero.me/v2/predict/text';
    private $wordFrequency = null;

    public function __construct()
    {
        $this->gptzeroApiKey = config('services.gptzero.api_key');
    }

    public function detect($text)
    {
        if ($this->gptzeroApiKey) {
            try {
                return $this->detectViaGptzero($text);
            } catch (\Exception $e) {
                Log::warning('GPTZero detection failed, fallback to local NLP engine: ' . $e->getMessage());
            }
        }

        return $this->detectLocal($text);
    }

    public function detectViaGptzero($text)
    {
        $chunks = str_split($text, 2000);

        $totalResults = [
            'documents' => [],
            'overall_ai_probability' => 0,
            'overall_human_probability' => 0,
        ];

        foreach ($chunks as $chunk) {
            if (strlen(trim($chunk)) < 30) continue;

            $response = Http::withHeaders([
                'x-api-key' => $this->gptzeroApiKey,
                'Accept' => 'application/json',
            ])->post($this->gptzeroUrl, [
                'document' => $chunk,
            ]);

            if (!$response->successful()) {
                throw new \Exception('GPTZero API request failed (HTTP ' . $response->status() . '): ' . $response->body());
            }

            $data = $response->json();
            $docResult = $data['documents'][0] ?? [];

            $aiProb = $docResult['completely_generated_prob'] ?? ($docResult['class_probabilities']['ai'] ?? 0);
            $humanProb = $docResult['likely_written_by_human'] ?? ($docResult['class_probabilities']['human'] ?? (1 - $aiProb));

            $totalResults['documents'][] = [
                'content' => substr($chunk, 0, 100) . '...',
                'ai_probability' => $aiProb,
                'human_probability' => $humanProb,
                'mixed_probability' => $docResult['mixed_prob'] ?? ($docResult['class_probabilities']['mixed'] ?? 0),
            ];

            $totalResults['overall_ai_probability'] = max($totalResults['overall_ai_probability'], $aiProb);
        }

        $overallAi = round($totalResults['overall_ai_probability'] * 100, 2);
        $overallHuman = round(100 - $overallAi, 2);

        return [
            'ai_probability' => $overallAi,
            'human_probability' => $overallHuman,
            'detected_models' => ['gpt-4', 'gpt-3.5', 'claude', 'bard'],
            'detection_method' => 'gptzero_api',
            'confidence_score' => 95,
            'details' => $totalResults,
        ];
    }

    // ===================================================================
    // LOCAL DETECTION ENGINE v4 — Improved accuracy
    // ===================================================================

    /**
     * Top ~500 English words by frequency.
     * Key = word, Value = rank (0-indexed, lower = more common).
     * Used as a proxy for unigram language model probabilities.
     */
    private function getWordFrequency()
    {
        if ($this->wordFrequency === null) {
            $words = [
                // Rank 0-49: Ultra-common function words
                'the','be','to','of','and','a','in','that','have','i',
                'it','for','not','on','with','he','as','you','do','at',
                'this','but','his','by','from','they','we','say','her','she',
                'or','an','will','my','one','all','would','there','their','what',
                'so','up','out','if','about','who','get','which','go','me',
                // Rank 50-99
                'when','make','can','like','time','no','just','him','know','take',
                'people','into','year','your','good','some','could','them','see','other',
                'than','then','now','look','only','come','its','over','think','also',
                'back','after','use','two','how','our','work','first','well','way',
                'even','new','want','because','any','these','give','day','most','us',
                // Rank 100-149
                'find','here','thing','many','very','still','need','much','right','before',
                'been','through','own','tell','being','call','down','should','last','long',
                'great','since','against','old','too','same','around','where','however','may',
                'run','small','try','end','off','never','why','another','keep','must',
                'between','turn','always','those','point','start','show','hand','high','part',
                // Rank 150-199
                'place','home','every','number','head','under','world','let','help','line',
                'while','live','without','move','change','feel','might','few','next','seem',
                'play','group','side','stand','young','leave','question','system','set','each',
                'state','life','put','house','read','case','such','become','city','open',
                'close','night','country','begin','story','report','build','school','power','early',
                // Rank 200-249
                'real','during','water','name','follow','different','often','away','learn',
                'program','problem','body','yet','face','room','public','grow','together','order',
                'allow','talk','level','hold','both','result','area','money','fact','sure',
                'bring','already','possible','hear','second','person','child','quite','within','among',
                'develop','company','believe','later','team','large','idea','interest','several',
                // Rank 250-299
                'word','ask','late','include','continue','important','lose','once','until','along',
                'form','present','happen','provide','sit','create','month','family','perhaps','best',
                'far','political','social','issue','example','carry','age','offer','write',
                'support','government','door','increase','course','control','produce','free','able','market',
                'role','sense','service','cost','strong','study','war','understand','student','health',
                // Rank 300-349
                'clear','food','land','least','add','human','information','expect','plan','class',
                'law','above','actually','research','air','community','care','view','town','value',
                'reason','process','action','model','data','figure','road','policy','education','force',
                'field','media','condition','space','practice','fund','college','past','death','art',
                'risk','table','rate','president','period','main','record','teacher','simply','evidence',
                // Rank 350-399
                'personal','experience','effect','likely','term','history','loss','effort','upon','note',
                'rise','share','moment','return','special','general','natural','property','staff','design',
                'activity','culture','material','analysis','region','approach','nation','performance','apply','technology',
                'detail','response','focus','success','attention','economy','cover','resource','source','pattern',
                'rule','strategy','management','structure','standard','describe','trade','treatment','type','benefit',
                // Rank 400-449
                'range','significant','similar','official','project','individual','test','basic','skill','method',
                'protect','position','measure','environment','growth','population','identify','theory','challenge','lack',
                'feature','knowledge','item','traditional','particular','critical','current','physical','major','concern',
                'review','impact','assume','article','suggest','require','rather','final','maintain','address',
                'security','relate','common','specific','account','opinion','series','variety','access','occur',
                // Rank 450-499
                'contain','image','character','quality','primary','responsible','define','financial','purpose','involve',
                'potential','professional','task','status','claim','complex','contribute','consider','entire',
                'separate','remain','context','establish','previous','indicate','reflect','participate','generation',
                'reduce','achieve','determine','original','decade','agency','demand','emerge','reference','statement',
                'overall','opportunity','commission','represent','essential','perspective','release','alternative','debate',
            ];
            $this->wordFrequency = array_flip($words);
        }
        return $this->wordFrequency;
    }

    private function detectLocal($text)
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $sentences = array_filter($sentences, fn($s) => str_word_count(trim($s)) >= 3);
        $sentences = array_values($sentences);

        if (count($sentences) < 2) {
             return [
                'ai_probability' => 0,
                'human_probability' => 100,
                'detected_models' => [],
                'detection_method' => 'local_insufficient_data',
                'confidence_score' => 10,
                'details' => ['error' => 'Not enough sentences to analyze for burstiness.'],
            ];
        }

        $allWords = str_word_count(strtolower($text), 1);
        if (count($allWords) < 20) {
            return [
                'ai_probability' => 0,
                'human_probability' => 100,
                'detected_models' => [],
                'detection_method' => 'local_insufficient_data',
                'confidence_score' => 20,
                'details' => ['error' => 'Not enough text content to analyze'],
            ];
        }

        $sentencePerplexities = array_map([$this, 'calculateSentencePerplexity'], $sentences);

        $logits = [];
        $logits['perplexity'] = $this->calculateOverallPerplexity(array_sum($sentencePerplexities) / count($sentencePerplexities));
        $logits['burstiness'] = $this->calculateBurstiness($sentencePerplexities);
        $logits['lexical_diversity'] = $this->calculateLexicalDiversity($allWords);
        $logits['ai_phrase_frequency'] = $this->calculateAiPhraseFrequency($text, count($allWords));
        $logits['repetition'] = $this->calculateRepetitionScore($allWords);
        $logits['sentence_length_variance'] = $this->calculateSentenceLengthVariance($sentences);
        $logits['readability'] = $this->calculateReadability($text, count($sentences), count($allWords));
        $logits['pos_distribution'] = $this->calculatePosDistributionProxy($allWords);
        $logits['transition_words'] = $this->calculateTransitionWordsScore($text, count($allWords));
        $logits['punctuation'] = $this->calculatePunctuationScore($text);
        // New v4 signals
        $logits['sentence_opening_diversity'] = $this->calculateSentenceOpeningDiversity($sentences);
        $logits['paragraph_uniformity'] = $this->calculateParagraphUniformity($text);
        $logits['contraction_avoidance'] = $this->calculateContractionAvoidance($text);
        $logits['pronoun_pattern'] = $this->calculatePronounPattern($allWords);
        $logits['hedging_language'] = $this->calculateHedgingLanguage($text, count($allWords));


        $finalLogit = $this->calculateFinalLogit($logits);
        $aiProbability = 100 / (1 + exp(-$finalLogit)); // Logistic function

        return [
            'ai_probability' => round($aiProbability, 2),
            'human_probability' => round(100 - $aiProbability, 2),
            'detected_models' => $this->guessAiModel($logits, $aiProbability),
            'detection_method' => 'local_algorithm_v4',
            'confidence_score' => $this->calculateConfidence($logits, $finalLogit, count($allWords)),
            'details' => $logits,
        ];
    }
    
    private function calculateSentencePerplexity(string $sentence)
    {
        $words = str_word_count(strtolower($sentence), 1);
        if (empty($words)) {
            return 0;
        }

        $freqData = $this->getWordFrequency();
        $totalWords = count($words);
        $vocabSize = count($freqData);
        $unknownPenalty = $vocabSize * 1.2;

        $totalLogProb = 0;
        foreach ($words as $word) {
            $rank = $freqData[$word] ?? $unknownPenalty;
            // Simplified perplexity: higher rank (less common) = higher score
            $totalLogProb += log($rank + 1); // +1 to avoid log(0)
        }
        
        return $totalLogProb / $totalWords; // Average log probability
    }

    private function calculateOverallPerplexity(float $avgPerplexity)
    {
        // With a 500-word frequency list, the average perplexity score range shifts.
        // AI text clusters around 3.0-4.0 (uses common words predictably).
        // Human text spreads wider, often 4.5-6.5+ (more varied vocabulary).
        // Sigmoid centered at 4.2, steepness 1.8
        $logit = (4.2 - $avgPerplexity) * 1.8;
        return max(-3.0, min(3.0, $logit));
    }
    
    private function calculateBurstiness(array $sentencePerplexities)
    {
        if (count($sentencePerplexities) < 2) {
            return 0; // Not enough data
        }
        $stddev = $this->calculateStdDev($sentencePerplexities);
        
        // High variance is human-like ("bursty"). Low variance is AI-like.
        // Smooth sigmoid centered at 0.5, steepness 3.0
        $logit = (0.5 - $stddev) * 3.0;
        return max(-3.0, min(3.0, $logit));
    }
    
    /**
     * Corrected Type-Token Ratio (CTTR) — length-normalized lexical diversity.
     * CTTR = unique_words / sqrt(2 * total_words)
     */
    private function calculateLexicalDiversity(array $words)
    {
        $totalWords = count($words);
        if ($totalWords < 10) return 0;
        
        $uniqueWords = count(array_unique($words));
        $cttr = $uniqueWords / sqrt(2 * $totalWords);

        // AI text typically has CTTR in range 4.5-6.5 (mid-range, predictable vocabulary).
        // Human text is more variable: either very low (casual) or very high (literary).
        // Smooth sigmoid: center at 5.5, steepness 0.8
        $deviation = abs($cttr - 5.5);
        // If close to center (AI zone), positive logit; if far, negative
        $logit = (1.5 - $deviation) * 0.8;
        return max(-2.0, min(2.0, $logit));
    }
    
    /**
     * Expanded AI phrase detection with tiered scoring and density normalization.
     */
    private function calculateAiPhraseFrequency($text, int $wordCount)
    {
        // Strong AI indicators (weight 2.0 each)
        $strongPhrases = [
            'delve into', 'delve deeper', 'it is important to note', 'it is worth noting',
            'it\'s worth noting', 'plays a crucial role', 'plays a pivotal role',
            'in today\'s digital era', 'in today\'s world', 'in today\'s fast-paced',
            'holistic approach', 'seamlessly integrated', 'foster a deeper',
            'tapestry of', 'multifaceted approach', 'ever-evolving landscape',
            'it is essential to', 'one cannot overstate', 'stands as a testament',
            'serves as a cornerstone', 'navigating the complexities', 'at its core',
            'unlocking the potential', 'paradigm shift', 'at the forefront',
            'in the realm of', 'it cannot be overstated', 'a myriad of',
        ];

        // Medium AI indicators (weight 1.0 each)
        $mediumPhrases = [
            'comprehensive understanding', 'nuanced understanding', 'in summary',
            'in conclusion', 'to summarize', 'in essence', 'by and large',
            'the landscape of', 'the intricacies of', 'shed light on',
            'a testament to', 'pave the way', 'underscore the importance',
            'a profound impact', 'a deeper understanding', 'in the context of',
            'with that being said', 'having said that', 'on the other hand',
            'it should be noted', 'as a matter of fact', 'by the same token',
            'in light of', 'for the purpose of', 'in terms of',
        ];

        // Weak AI indicators (weight 0.5 each)
        $weakPhrases = [
            'comprehensive', 'transformative', 'facilitate', 'utilize',
            'leverage', 'robust', 'streamline', 'optimize', 'enhance',
            'empower', 'innovative', 'cutting-edge', 'groundbreaking',
            'revolutionize', 'pivotal', 'synergy', 'proactive',
            'endeavor', 'encompass', 'foster',
        ];
        
        $lowerText = strtolower($text);
        $weightedScore = 0;

        foreach ($strongPhrases as $phrase) {
            $weightedScore += substr_count($lowerText, $phrase) * 2.0;
        }
        foreach ($mediumPhrases as $phrase) {
            $weightedScore += substr_count($lowerText, $phrase) * 1.0;
        }
        foreach ($weakPhrases as $phrase) {
            $weightedScore += substr_count($lowerText, $phrase) * 0.5;
        }

        // Normalize by text length (per 100 words)
        $densityPer100 = ($wordCount > 0) ? ($weightedScore / $wordCount) * 100 : 0;

        // Smooth sigmoid: center at 1.5 density, steepness 1.5
        $logit = ($densityPer100 - 1.0) * 1.5;
        return max(-2.5, min(3.0, $logit));
    }

    private function calculateRepetitionScore(array $words)
    {
        if (count($words) < 10) return 0;
        
        // Use both bigrams and trigrams
        $bigrams = [];
        $trigrams = [];
        for ($i = 0; $i < count($words) - 1; $i++) {
            $bigrams[] = $words[$i] . ' ' . $words[$i+1];
        }
        for ($i = 0; $i < count($words) - 2; $i++) {
            $trigrams[] = $words[$i] . ' ' . $words[$i+1] . ' ' . $words[$i+2];
        }

        $bigramRepRate = empty($bigrams) ? 0 : 1 - (count(array_unique($bigrams)) / count($bigrams));
        $trigramRepRate = empty($trigrams) ? 0 : 1 - (count(array_unique($trigrams)) / count($trigrams));

        // Weighted average: trigrams more informative
        $combinedRate = ($bigramRepRate * 0.3) + ($trigramRepRate * 0.7);

        // Smooth sigmoid: center at 0.08, steepness 25
        $logit = ($combinedRate - 0.08) * 25;
        return max(-2.0, min(2.5, $logit));
    }

    private function calculateSentenceLengthVariance(array $sentences)
    {
        $lengths = array_map(function($s) { return str_word_count($s); }, $sentences);
        if (count($lengths) < 2) return 0;

        $stddev = $this->calculateStdDev($lengths);
        $mean = array_sum($lengths) / count($lengths);
        
        // Coefficient of variation (CV) — normalized variance
        $cv = ($mean > 0) ? $stddev / $mean : 0;

        // AI tends to have CV < 0.3 (uniform sentences). Humans > 0.4.
        // Smooth sigmoid: center at 0.35, steepness 6
        $logit = (0.35 - $cv) * 6;
        return max(-2.5, min(2.5, $logit));
    }

    private function countSyllables(string $word) {
        $word = strtolower($word);
        if (strlen($word) <= 3) return 1;

        $word = preg_replace('/(es|ed|e)$/', '', $word);
        $vowels = ['a', 'e', 'i', 'o', 'u', 'y'];
        $count = 0;
        $inVowelGroup = false;

        for ($i = 0; $i < strlen($word); $i++) {
            $isVowel = in_array($word[$i], $vowels);
            if ($isVowel && !$inVowelGroup) {
                $count++;
                $inVowelGroup = true;
            } elseif (!$isVowel) {
                $inVowelGroup = false;
            }
        }
        return $count > 0 ? $count : 1;
    }

    private function calculateReadability(string $text, int $sentenceCount, int $wordCount)
    {
        if ($wordCount < 10 || $sentenceCount < 1) return 0;

        $syllableCount = 0;
        foreach (str_word_count(strtolower($text), 1) as $word) {
            $syllableCount += $this->countSyllables($word);
        }
        
        // Flesch Reading Ease score
        $score = 206.835 - 1.015 * ($wordCount / $sentenceCount) - 84.6 * ($syllableCount / $wordCount);

        // AI typically writes in the 40-65 Flesch range (moderately difficult).
        // Smooth sigmoid centered at 52.5 (AI sweet spot), with falloff
        $deviation = abs($score - 52.5);
        $logit = (15 - $deviation) * 0.06;
        return max(-1.5, min(1.5, $logit));
    }

    private function calculatePosDistributionProxy(array $words)
    {
        $commonWords = $this->getWordFrequency();
        $commonWordCount = 0;
        foreach ($words as $word) {
            if (isset($commonWords[$word])) {
                $commonWordCount++;
            }
        }
        
        $ratio = $commonWordCount / count($words);

        // Smooth sigmoid centered at 0.52 (AI typical ratio), steepness 10
        $deviation = abs($ratio - 0.52);
        $logit = (0.08 - $deviation) * 10;
        return max(-1.5, min(1.5, $logit));
    }

    /**
     * Expanded transition word list with density normalization.
     */
    private function calculateTransitionWordsScore($text, int $wordCount)
    {
        $transitionWords = [
            // Additive
            'furthermore', 'moreover', 'additionally', 'in addition', 'likewise',
            'correspondingly', 'equally important',
            // Adversative
            'however', 'nevertheless', 'nonetheless', 'on the contrary',
            'conversely', 'in contrast', 'notwithstanding', 'albeit',
            // Causal
            'therefore', 'consequently', 'thus', 'hence', 'accordingly',
            'as a result', 'for this reason',
            // Sequential
            'subsequently', 'henceforth', 'in the first place',
            'to begin with', 'in the meantime',
        ];

        $lowerText = strtolower($text);
        $matches = 0;
        foreach ($transitionWords as $word) {
            $matches += substr_count($lowerText, $word);
        }

        // Normalize per 100 words
        $densityPer100 = ($wordCount > 0) ? ($matches / $wordCount) * 100 : 0;

        // AI typically uses 1.5-3.0 transition words per 100 words.
        // Smooth sigmoid: center at 1.5, steepness 2.0
        $logit = ($densityPer100 - 1.0) * 2.0;
        return max(-2.0, min(2.5, $logit));
    }

    private function calculatePunctuationScore($text)
    {
        $commaCount = substr_count($text, ',');
        $semicolonCount = substr_count($text, ';');
        $colonCount = substr_count($text, ':');

        $sentenceCount = preg_match_all('/[.!?]+/', $text);
        if ($sentenceCount == 0) return 0;

        $commaPerSentence = $commaCount / $sentenceCount;
        $semicolonRatio = $semicolonCount / max(1, $sentenceCount);

        // AI tends toward consistent comma usage (1.5-2.5 per sentence)
        // and rarely uses semicolons
        $commaDeviation = abs($commaPerSentence - 2.0);
        $commaLogit = (1.0 - $commaDeviation) * 0.5;

        // Semicolon absence is slightly AI-like
        $semicolonLogit = ($semicolonRatio < 0.05) ? 0.3 : -0.2;

        $logit = ($commaLogit * 0.7) + ($semicolonLogit * 0.3);
        return max(-1.5, min(1.5, $logit));
    }

    // ===================================================================
    // NEW v4 SIGNALS
    // ===================================================================

    /**
     * Measures diversity of sentence opening words.
     * AI tends to start sentences with repetitive patterns (The, This, It, In, etc.)
     */
    private function calculateSentenceOpeningDiversity(array $sentences)
    {
        if (count($sentences) < 5) return 0;

        $openings = [];
        foreach ($sentences as $sentence) {
            $words = str_word_count(strtolower(trim($sentence)), 1);
            if (!empty($words)) {
                // Use first 2 words as the opening pattern
                $opening = $words[0] . (isset($words[1]) ? ' ' . $words[1] : '');
                $openings[] = $opening;
            }
        }

        if (empty($openings)) return 0;

        $totalOpenings = count($openings);
        $uniqueOpenings = count(array_unique($openings));
        $diversityRatio = $uniqueOpenings / $totalOpenings;

        // Also check for single-word opening repetition
        $firstWords = array_map(fn($o) => explode(' ', $o)[0], $openings);
        $firstWordCounts = array_count_values($firstWords);
        $maxFirstWordFreq = max($firstWordCounts) / $totalOpenings;

        // Low diversity = AI-like. High diversity = Human-like.
        // Smooth sigmoid: center at 0.65, steepness 5
        $diversityLogit = (0.65 - $diversityRatio) * 5;

        // If any single first word dominates (>40%), strong AI signal
        $dominanceLogit = ($maxFirstWordFreq > 0.35) ? ($maxFirstWordFreq - 0.35) * 5 : -0.3;

        $logit = ($diversityLogit * 0.6) + ($dominanceLogit * 0.4);
        return max(-2.5, min(2.5, $logit));
    }

    /**
     * Measures uniformity of paragraph lengths.
     * AI-generated paragraphs tend to be remarkably uniform in length.
     */
    private function calculateParagraphUniformity(string $text)
    {
        // Split by double newlines or paragraph breaks
        $paragraphs = preg_split('/\n\s*\n/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $paragraphs = array_filter($paragraphs, fn($p) => str_word_count(trim($p)) >= 5);
        $paragraphs = array_values($paragraphs);

        if (count($paragraphs) < 3) return 0;

        $lengths = array_map(fn($p) => str_word_count($p), $paragraphs);
        $mean = array_sum($lengths) / count($lengths);
        $cv = ($mean > 0) ? $this->calculateStdDev($lengths) / $mean : 0;

        // AI paragraphs: CV typically < 0.25. Human: CV > 0.4.
        // Smooth sigmoid: center at 0.30, steepness 6
        $logit = (0.30 - $cv) * 6;
        return max(-2.0, min(2.0, $logit));
    }

    /**
     * Detects avoidance of contractions — a strong AI signal.
     * AI text tends to use "do not" instead of "don't", "it is" instead of "it's", etc.
     */
    private function calculateContractionAvoidance(string $text)
    {
        $lowerText = strtolower($text);

        // Contraction => expanded form pairs
        $contractionPairs = [
            "don't"     => 'do not',
            "doesn't"   => 'does not',
            "didn't"    => 'did not',
            "won't"     => 'will not',
            "wouldn't"  => 'would not',
            "couldn't"  => 'could not',
            "shouldn't" => 'should not',
            "can't"     => ['cannot', 'can not'],
            "isn't"     => 'is not',
            "aren't"    => 'are not',
            "wasn't"    => 'was not',
            "weren't"   => 'were not',
            "hasn't"    => 'has not',
            "haven't"   => 'have not',
            "hadn't"    => 'had not',
            "it's"      => 'it is',
            "that's"    => 'that is',
            "there's"   => 'there is',
            "here's"    => 'here is',
            "what's"    => 'what is',
            "let's"     => 'let us',
            "i'm"       => 'i am',
            "you're"    => 'you are',
            "they're"   => 'they are',
            "we're"     => 'we are',
            "i've"      => 'i have',
            "you've"    => 'you have',
            "they've"   => 'they have',
            "we've"     => 'we have',
            "i'll"      => 'i will',
            "you'll"    => 'you will',
            "they'll"   => 'they will',
            "we'll"     => 'we will',
            "i'd"       => 'i would',
        ];

        $contractionCount = 0;
        $expandedCount = 0;

        foreach ($contractionPairs as $contraction => $expanded) {
            $contractionCount += substr_count($lowerText, $contraction);
            if (is_array($expanded)) {
                foreach ($expanded as $exp) {
                    $expandedCount += substr_count($lowerText, $exp);
                }
            } else {
                $expandedCount += substr_count($lowerText, $expanded);
            }
        }

        $totalForms = $contractionCount + $expandedCount;
        if ($totalForms < 2) return 0; // Not enough data

        // Ratio of expanded forms to total: high = AI, low = human
        $expandedRatio = $expandedCount / $totalForms;

        // Smooth sigmoid: center at 0.5, steepness 4
        $logit = ($expandedRatio - 0.5) * 4;
        return max(-2.0, min(2.5, $logit));
    }

    /**
     * Analyzes pronoun usage patterns.
     * AI tends to use fewer first-person pronouns and more third-person/impersonal constructions.
     */
    private function calculatePronounPattern(array $words)
    {
        $totalWords = count($words);
        if ($totalWords < 30) return 0;

        $firstPerson = ['i', 'me', 'my', 'mine', 'myself', 'we', 'us', 'our', 'ours', 'ourselves'];
        $thirdPerson = ['he', 'she', 'it', 'him', 'her', 'his', 'its', 'they', 'them', 'their', 'theirs'];
        $impersonal = ['one', 'someone', 'anyone', 'everyone', 'nobody'];

        $firstCount = 0;
        $thirdCount = 0;
        $impersonalCount = 0;

        foreach ($words as $word) {
            if (in_array($word, $firstPerson)) $firstCount++;
            elseif (in_array($word, $thirdPerson)) $thirdCount++;
            elseif (in_array($word, $impersonal)) $impersonalCount++;
        }

        $totalPronouns = $firstCount + $thirdCount + $impersonalCount;
        if ($totalPronouns < 2) return 0;

        // AI signal: low first-person ratio + high impersonal ratio
        $firstPersonRatio = $firstCount / $totalPronouns;
        $impersonalRatio = $impersonalCount / max(1, $totalPronouns);

        // Low first-person is AI-like in student writing context
        $logit = (0.3 - $firstPersonRatio) * 2.0 + ($impersonalRatio * 3.0);
        return max(-1.5, min(1.5, $logit));
    }

    /**
     * Detects hedging language density — a pattern common in AI text.
     */
    private function calculateHedgingLanguage(string $text, int $wordCount)
    {
        $hedgingPhrases = [
            'it is important to', 'it is crucial to', 'it is essential to',
            'it is worth', 'it should be noted', 'it must be noted',
            'one might argue', 'one could argue', 'it can be said',
            'it can be argued', 'it is widely', 'it is generally',
            'to a certain extent', 'to some extent', 'in many ways',
            'in various ways', 'plays a significant', 'plays an important',
            'there are several', 'there are many', 'there are numerous',
            'this highlights', 'this demonstrates', 'this illustrates',
            'this underscores', 'this emphasizes', 'this suggests',
        ];

        $lowerText = strtolower($text);
        $matches = 0;
        foreach ($hedgingPhrases as $phrase) {
            $matches += substr_count($lowerText, $phrase);
        }

        // Normalize per 100 words
        $densityPer100 = ($wordCount > 0) ? ($matches / $wordCount) * 100 : 0;

        // Smooth sigmoid: center at 0.5 density, steepness 3
        $logit = ($densityPer100 - 0.3) * 3.0;
        return max(-1.5, min(2.0, $logit));
    }

    // ===================================================================
    // SCORING & AGGREGATION
    // ===================================================================

    private function calculateFinalLogit(array $logits)
    {
        $weights = [
            'perplexity'                 => 0.15,
            'burstiness'                 => 0.18,
            'lexical_diversity'          => 0.08,
            'ai_phrase_frequency'        => 0.10,
            'repetition'                 => 0.07,
            'sentence_length_variance'   => 0.07,
            'readability'                => 0.04,
            'pos_distribution'           => 0.04,
            'transition_words'           => 0.05,
            'punctuation'                => 0.03,
            'sentence_opening_diversity' => 0.07,
            'paragraph_uniformity'       => 0.04,
            'contraction_avoidance'      => 0.04,
            'pronoun_pattern'            => 0.02,
            'hedging_language'           => 0.02,
        ];
        
        $finalLogit = 0;
        foreach ($weights as $key => $weight) {
            $finalLogit += ($logits[$key] ?? 0) * $weight;
        }

        // Small bias towards human to reduce false positives
        $finalLogit -= 0.1;
        
        return $finalLogit;
    }

    /**
     * Improved confidence scoring using signal agreement.
     * When most signals agree on direction, confidence is high.
     * When signals disagree, confidence drops.
     */
    private function calculateConfidence(array $logits, float $finalLogit, int $wordCount)
    {
        // 1. Base confidence from logit magnitude (how far from decision boundary)
        $baseConfidence = 1 - (1 / (1 + abs($finalLogit)));

        // 2. Signal agreement factor
        $positiveSignals = 0;
        $negativeSignals = 0;
        $activeSignals = 0;

        foreach ($logits as $value) {
            if (abs($value) < 0.1) continue; // Skip near-zero (neutral) signals
            $activeSignals++;
            if ($value > 0) $positiveSignals++;
            else $negativeSignals++;
        }

        // Agreement ratio: 1.0 = all signals agree, 0.5 = perfectly split
        $agreementRatio = ($activeSignals > 0)
            ? max($positiveSignals, $negativeSignals) / $activeSignals
            : 0.5;

        // 3. Text length modifier
        $lengthModifier = min(1.0, $wordCount / 200);

        // 4. Combine: base confidence x agreement x length
        $confidence = (40 + ($baseConfidence * 50)) * $agreementRatio * $lengthModifier;

        return round(max(10, min(95, $confidence)), 2);
    }
    
    private function calculateStdDev(array $values)
    {
        $count = count($values);
        if ($count < 2) return 0;

        $mean = array_sum($values) / $count;
        $variance = 0.0;
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }
        return sqrt($variance / ($count - 1)); // Sample standard deviation
    }

    /**
     * Style-based labeling instead of unreliable model-specific guessing.
     */
    private function guessAiModel(array $logits, $aiProbability)
    {
        if ($aiProbability < 40) {
            return [];
        }

        $models = [];

        // Formal-structured: high readability in AI zone + high transition word usage + low contractions
        $formalScore = (($logits['readability'] ?? 0) + ($logits['transition_words'] ?? 0) + ($logits['contraction_avoidance'] ?? 0)) / 3;
        if ($formalScore > 0.5) {
            $models[] = 'formal-structured';
        }

        // Conversational-AI: uses some contractions but still has AI phrase patterns
        if (($logits['contraction_avoidance'] ?? 0) < 0.3 && ($logits['ai_phrase_frequency'] ?? 0) > 0.5) {
            $models[] = 'conversational-ai';
        }

        // Highly uniform: strong burstiness + sentence uniformity signals
        if (($logits['burstiness'] ?? 0) > 0.8 && ($logits['sentence_length_variance'] ?? 0) > 0.8) {
            $models[] = 'highly-uniform';
        }

        return !empty($models) ? array_unique($models) : ['generic-llm'];
    }
}
