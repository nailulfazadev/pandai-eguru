import os
import glob
import re

directory = '/Users/mymac/Documents/belajar/studioajar.md/studioajar-app/resources/views/tools/'

files = glob.glob(os.path.join(directory, '*.blade.php'))

for file in files:
    with open(file, 'r') as f:
        content = f.read()

    # Remove the checked conditional for use_mock
    content = content.replace("{{ config('app.env') === 'local' ? 'checked' : '' }}", "")

    with open(file, 'w') as f:
        f.write(content)

print("Done")
