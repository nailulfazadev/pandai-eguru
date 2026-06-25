import os

def replace_in_files(directory, old_str, new_str):
    for root, dirs, files in os.walk(directory):
        if any(ignored in root for ignored in ['/vendor', '/storage', '/.git', '/node_modules']):
            continue
        for file in files:
            if not file.endswith(('.php', '.js', '.json', '.md', '.vue', '.css', '.html')):
                continue
            filepath = os.path.join(root, file)
            try:
                with open(filepath, 'r', encoding='utf-8') as f:
                    content = f.read()
                
                # Replace the exact brand name
                new_content = content.replace("StudioAjar", "PandAI")
                # Replace domain references if any
                new_content = new_content.replace("studioajar.com", "pandai.com")

                if new_content != content:
                    with open(filepath, 'w', encoding='utf-8') as f:
                        f.write(new_content)
                    print(f"Updated {filepath}")
            except Exception as e:
                pass

replace_in_files('/Users/mymac/Documents/belajar/studioajar.md/studioajar-app', 'StudioAjar', 'PandAI')
