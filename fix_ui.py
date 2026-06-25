import glob
import os

replacements = {
    'class="w-full form-input"': 'class="w-full border-2 border-cloud-gray rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition"',
    'class="w-full form-textarea"': 'class="w-full border-2 border-cloud-gray rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-sky-blue bg-[#f9f9f9] transition resize-y"',
    'btn-primary': 'btn-3d-primary'
}

files = glob.glob('resources/views/classrooms/*.blade.php') + glob.glob('resources/views/journals/*.blade.php')

for file_path in files:
    with open(file_path, 'r') as f:
        content = f.read()
    
    for old, new in replacements.items():
        content = content.replace(old, new)
        
    with open(file_path, 'w') as f:
        f.write(content)
    print(f"Fixed UI in {file_path}")

