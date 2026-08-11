#!/usr/bin/env python3

import sys
import json
import asyncio
import base64
from pathlib import Path

try:
    from notebooklm import NotebookLMClient
except ImportError:
    NotebookLMClient = None

async def generate_notebooklm_audio(text, title, output_path, storage_path=None):
    if NotebookLMClient is None:
        raise Exception("notebooklm-py package is not installed on this system.")

    # Initialize client from storage state
    if storage_path and Path(storage_path).exists() and Path(storage_path).stat().st_size > 10:
        try:
            client_ctx = NotebookLMClient.from_storage(path=storage_path)
        except Exception:
            client_ctx = NotebookLMClient.from_storage()
    else:
        client_ctx = NotebookLMClient.from_storage()

    async with client_ctx as client:
        # Create Notebook
        notebook = await client.notebooks.create(title=title)
        notebook_id = notebook.id

        # Add Source Text (with extended wait_timeout)
        source = await client.sources.add_text(
            notebook_id=notebook_id,
            title="Study Material",
            content=text,
            wait=True,
            wait_timeout=600.0
        )

        # Generate Audio Overview
        gen_status = await client.artifacts.generate_audio(notebook_id=notebook_id)

        # Wait for audio generation completion (up to 20 mins)
        completed_status = await client.artifacts.wait_for_completion(
            notebook_id=notebook_id,
            task_id=gen_status.task_id,
            timeout=1200
        )

        if completed_status.is_failed:
            raise Exception(f"NotebookLM Audio Generation Failed: {completed_status.error or 'Unknown error'}")

        # Download MP3 file
        out_file = Path(output_path)
        out_file.parent.mkdir(parents=True, exist_ok=True)
        await client.artifacts.download_audio(
            notebook_id=notebook_id,
            output_path=str(out_file)
        )

        return {
            'success': True,
            'file': str(out_file),
            'notebook_id': notebook_id,
            'task_id': gen_status.task_id
        }

def main():
    args = {}

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
                    print(json.dumps({'success': False, 'error': f'Invalid STDIN input: {str(e)}'}))
                    sys.exit(1)

    if not args and len(sys.argv) > 1:
        raw_input = sys.argv[1]
        try:
            args = json.loads(raw_input)
        except Exception:
            try:
                decoded = base64.b64decode(raw_input).decode('utf-8')
                args = json.loads(decoded)
            except Exception:
                pass

    if not args:
        print(json.dumps({'success': False, 'error': 'Missing input arguments.'}))
        sys.exit(1)

    text = args.get('text', '')
    title = args.get('title', 'NotebookLM Study Overview')
    output_path = args.get('output_path')
    storage_path = args.get('storage_path')

    if not text or not output_path:
        print(json.dumps({'success': False, 'error': 'Missing text or output_path'}))
        sys.exit(1)

    try:
        result = asyncio.run(generate_notebooklm_audio(text, title, output_path, storage_path))
        print(json.dumps(result))
    except Exception as e:
        print(json.dumps({'success': False, 'error': str(e)}))

if __name__ == '__main__':
    main()
