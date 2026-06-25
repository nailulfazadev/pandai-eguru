import os
import glob
import re

directory = '/Users/mymac/Documents/belajar/studioajar.md/studioajar-app/resources/views/tools/'

files = glob.glob(os.path.join(directory, '*.blade.php'))

for file in files:
    with open(file, 'r') as f:
        content = f.read()

    # First, remove the bad sed insertion
    content = content.replace("<!-- Mode Demo (Hemat Kuota AI) -->\n                <div style=\"display:none;\">", "<!-- Mode Demo (Hemat Kuota AI) -->")
    content = content.replace("<!-- Mode Demo Toggle -->\n                <div style=\"display:none;\">", "<!-- Mode Demo Toggle -->")

    # Now, hide the actual wrapper. The wrapper is the div right after the comment.
    # We will use regex to find the comment and the next `<div class="` and add `hidden ` to it.
    
    # Pattern 1
    pattern1 = r'(<!-- Mode Demo \(Hemat Kuota AI\) -->\s*<div class=")([^"]*)'
    content = re.sub(pattern1, r'\1hidden \2', content)

    # Pattern 2
    pattern2 = r'(<!-- Mode Demo Toggle -->\s*<div class=")([^"]*)'
    content = re.sub(pattern2, r'\1hidden \2', content)

    with open(file, 'w') as f:
        f.write(content)

print("Done")
