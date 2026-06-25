import re

file_path = 'app/Http/Controllers/GeminiController.php'
with open(file_path, 'r') as f:
    content = f.read()

# Replace for Modul Ajar
old_config = """                    'generationConfig' => [
                        'maxOutputTokens' => 8192,
                        'temperature' => 0.3
                    ]"""
new_config = """                    'generationConfig' => [
                        'maxOutputTokens' => 8192,
                        'temperature' => 0.3
                    ],
                    'safetySettings' => [
                        ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE']
                    ]"""

content = content.replace(old_config, new_config)

# Replace for Generator Soal
old_config_soal = """                    'generationConfig' => [
                        'maxOutputTokens' => 8192,
                        'temperature' => 0.7
                    ]"""
new_config_soal = """                    'generationConfig' => [
                        'maxOutputTokens' => 8192,
                        'temperature' => 0.7
                    ],
                    'safetySettings' => [
                        ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                        ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE']
                    ]"""

content = content.replace(old_config_soal, new_config_soal)

with open(file_path, 'w') as f:
    f.write(content)

print("Safety settings updated.")
