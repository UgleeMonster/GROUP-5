import os
import re

# Set this to your project folder path
PROJECT_DIR = "path/to/your/project"

# Regex patterns for comments
patterns = {
    'php_js_css': re.compile(r'(//.*?$|/\*.*?\*/)', re.DOTALL | re.MULTILINE),
    'html': re.compile(r'<!--.*?-->', re.DOTALL)
}

# File extensions to process
extensions = ['.php', '.js', '.css', '.html']

def remove_comments_from_file(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    if file_path.endswith('.html'):
        cleaned = re.sub(patterns['html'], '', content)
    else:
        cleaned = re.sub(patterns['php_js_css'], '', content)
    
    # Save cleaned file
    new_path = file_path.replace('.', '_clean.')
    with open(new_path, 'w', encoding='utf-8') as f:
        f.write(cleaned)
    
    print(f"Cleaned file saved as: {new_path}")

def process_folder(folder):
    for root, _, files in os.walk(folder):
        for file in files:
            if any(file.endswith(ext) for ext in extensions):
                file_path = os.path.join(root, file)
                remove_comments_from_file(file_path)

if __name__ == "__main__":
    process_folder(PROJECT_DIR)
    print("✅ Done! All files cleaned and saved as *_clean.* versions.")
