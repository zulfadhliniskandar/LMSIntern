#!/usr/bin/env python3

import asyncio
import sys
import json
import base64
import time
import re
from pathlib import Path
import edge_tts

def clean_text_for_tts(text: str) -> str:
    if not text:
        return ""
    
    trimmed = text.strip()
    if (trimmed.startswith('[') and trimmed.endswith(']')) or (trimmed.startswith('{') and trimmed.endswith('}')):
        try:
            data = json.loads(trimmed)
            if isinstance(data, list):
                for item in data:
                    if isinstance(item, dict) and 'text' in item:
                        item['text'] = clean_text_for_tts(item['text'])
                return json.dumps(data, ensure_ascii=False)
        except Exception:
            pass

    cleaned = re.sub(r'\[([^\]]+)\]\([^)]+\)', r'\1', text)
    cleaned = re.sub(r'```[\s\S]*?```', '', cleaned)
    cleaned = re.sub(r'`([^`]+)`', r'\1', cleaned)
    cleaned = cleaned.replace('`', '')
    cleaned = re.sub(r'\*{1,3}([^*]+)\*{1,3}', r'\1', cleaned)
    cleaned = re.sub(r'_{1,3}([^_]+)_{1,3}', r'\1', cleaned)
    cleaned = re.sub(r'~{1,2}([^~]+)~{1,2}', r'\1', cleaned)
    cleaned = re.sub(r'[*\u2022\u2023\u25E6\u2043\u204B~^\\\]]', '', cleaned)
    cleaned = re.sub(r'(?m)^\s*#+\s*', '', cleaned)
    cleaned = re.sub(r'#([a-zA-Z0-9]+)', r'\1', cleaned)
    cleaned = cleaned.replace('#', '')
    cleaned = re.sub(r'(?m)^\s*[\-–—+•*]\s+', '', cleaned)
    cleaned = re.sub(r'\s+[\-–—]\s+', ', ', cleaned)
    cleaned = cleaned.replace('|', ', ')
    cleaned = re.sub(r'([.!?,])\s*,+', r'\1', cleaned)
    cleaned = re.sub(r',\s*,+', ',', cleaned)
    cleaned = re.sub(r'[ \t]+', ' ', cleaned)
    cleaned = re.sub(r'\n{3,}', '\n\n', cleaned)
    return cleaned.strip()

VOICES = {
    'ms-MY': {
        'male': {
            'name': 'ms-MY-OsmanNeural',
            'styles': ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
        },
        'female': {
            'name': 'ms-MY-YasminNeural',
            'styles': ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
        }
    },
    'en-US': {
        'male': {
            'name': 'en-US-GuyNeural',
            'styles': ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
        },
        'female': {
            'name': 'en-US-JennyNeural',
            'styles': ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
        }
    },
    'en-GB': {
        'male': {
            'name': 'en-GB-ThomasNeural',
            'styles': ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
        },
        'female': {
            'name': 'en-GB-SoniaNeural',
            'styles': ['default', 'newscast', 'instruction', 'cheering', 'customer-service']
        }
    },
    'en-AU': {
        'male': {
            'name': 'en-AU-KenNeural',
            'styles': ['default', 'newscast']
        },
        'female': {
            'name': 'en-AU-NatashaNeural',
            'styles': ['default', 'newscast']
        }
    }
}

# 3-Person Podcast Speaker Voice Mapping per Language
PODCAST_SPEAKERS = {
    'ms-MY': {
        'host': 'ms-MY-OsmanNeural',        # Male BM Voice (Host: Alex)
        'expert': 'ms-MY-YasminNeural',     # Female BM Voice (Expert: Dr. Sarah)
        'student': 'ms-MY-OsmanNeural',     # Male BM Voice (Student: Jordan)
    },
    'en-US': {
        'host': 'en-US-GuyNeural',          # Male US Voice (Host: Alex)
        'expert': 'en-US-JennyNeural',      # Female US Voice (Expert: Dr. Sarah)
        'student': 'en-GB-ThomasNeural',    # Male UK Voice (Student: Jordan)
    }
}

async def generate_solo_speech(text, output_path, voice_name, rate=1.0, style='default'):
    """
    Generate standard single-voice speech using Edge TTS
    """
    text = clean_text_for_tts(text)

    if isinstance(rate, (int, float)):
        rate_pct = int(round((rate - 1.0) * 100))
        rate_str = f"{rate_pct:+d}%"
    else:
        rate_str = str(rate)

    out_file = Path(output_path)
    out_file.parent.mkdir(parents=True, exist_ok=True)

    communicate = edge_tts.Communicate(
        text=text,
        voice=voice_name,
        rate=rate_str,
    )

    await communicate.save(str(out_file))

    return {
        'success': True,
        'file': str(out_file),
        'voice': voice_name,
        'style': style,
        'rate': rate,
    }

async def generate_podcast_speech(dialogue_list, output_path, language='en-US', rate=1.0):
    """
    Generate 3-Person Podcast Speech with alternating voices per speaker turn.
    """
    speakers_map = PODCAST_SPEAKERS.get(language, PODCAST_SPEAKERS['en-US'])

    if isinstance(rate, (int, float)):
        rate_pct = int(round((rate - 1.0) * 100))
        rate_str = f"{rate_pct:+d}%"
    else:
        rate_str = str(rate)

    out_file = Path(output_path)
    out_file.parent.mkdir(parents=True, exist_ok=True)

    temp_clips = []
    timestamp = int(time.time() * 1000)

    for idx, turn in enumerate(dialogue_list):
        speaker_key = str(turn.get('speaker', 'host')).lower().strip()
        speech_text = clean_text_for_tts(turn.get('text', '')).strip()

        if not speech_text:
            continue

        voice_name = speakers_map.get(speaker_key, speakers_map['host'])
        clip_path = out_file.parent / f"temp_podcast_{timestamp}_{idx}.mp3"

        communicate = edge_tts.Communicate(
            text=speech_text,
            voice=voice_name,
            rate=rate_str,
        )

        await communicate.save(str(clip_path))
        temp_clips.append(clip_path)

    if not temp_clips:
        raise Exception("No valid dialogue lines found to synthesize podcast.")

    # Merge all individual dialogue MP3 clips into one file
    with open(out_file, 'wb') as merged_fp:
        for clip in temp_clips:
            if clip.exists():
                with open(clip, 'rb') as clip_fp:
                    merged_fp.write(clip_fp.read())
                clip.unlink(missing_ok=True)

    return {
        'success': True,
        'file': str(out_file),
        'voice': 'Multi-Speaker Podcast (3 Voices)',
        'style': 'podcast',
        'rate': rate,
    }

async def main():
    args = {}

    # Check STDIN first (bypasses Windows CLI length limit)
    if not sys.stdin.isatty():
        raw_input = sys.stdin.read().strip()
        if raw_input:
            try:
                args = json.loads(raw_input)
            except Exception:
                try:
                    decoded = base64.b64decode(raw_input).decode('utf-8')
                    args = json.loads(decoded)
                except Exception as e:
                    print(json.dumps({'error': f'Invalid STDIN input: {str(e)}'}))
                    sys.exit(1)

    # Fallback to CLI argument
    if not args and len(sys.argv) >= 2:
        raw_input = sys.argv[1]
        try:
            args = json.loads(raw_input)
        except Exception:
            pass

        if not args:
            try:
                decoded = base64.b64decode(raw_input).decode('utf-8')
                args = json.loads(decoded)
            except Exception as e:
                print(json.dumps({'error': f'Invalid JSON CLI input: {str(e)}'}))
                sys.exit(1)

    if not args:
        print(json.dumps({'error': 'No input parameters provided'}))
        sys.exit(1)

    text = args.get('text', '')
    output_path = args.get('output_path')
    voice_name = args.get('voice_name', 'en-US-JennyNeural')
    rate = float(args.get('rate', 1.0))
    style = args.get('style', 'default')
    generation_mode = args.get('generation_mode', 'solo')
    language = args.get('language', 'en-US')

    if not text or not output_path:
        print(json.dumps({'error': 'Missing text or output_path'}))
        sys.exit(1)

    rate = max(0.5, min(2.0, rate))

    try:
        if generation_mode == 'podcast':
            # Try parsing text as JSON dialogue array
            dialogue_list = []
            try:
                clean_json = text.strip()
                if clean_json.startswith('```'):
                    lines = clean_json.splitlines()
                    clean_json = '\n'.join([l for l in lines if not l.startswith('```')])
                dialogue_list = json.loads(clean_json)
            except Exception:
                pass

            if isinstance(dialogue_list, list) and len(dialogue_list) > 0:
                result = await generate_podcast_speech(dialogue_list, output_path, language, rate)
            else:
                # Fallback to solo speech if JSON dialogue parse failed
                result = await generate_solo_speech(text, output_path, voice_name, rate, style)
        else:
            result = await generate_solo_speech(text, output_path, voice_name, rate, style)

        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({'success': False, 'error': str(e)}))

if __name__ == '__main__':
    asyncio.run(main())
