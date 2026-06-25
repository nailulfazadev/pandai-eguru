import os
import glob

migration_files = glob.glob('database/migrations/*.php')

for file in migration_files:
    with open(file, 'r') as f:
        content = f.read()
    
    if "Schema::create" in content and "$table->engine" not in content:
        # Add $table->engine = 'InnoDB'; right after Schema::create(..., function (Blueprint $table) {
        content = content.replace(
            "function (Blueprint $table) {",
            "function (Blueprint $table) {\n            $table->engine = 'InnoDB';"
        )
        
        with open(file, 'w') as f:
            f.write(content)
            print(f"Added InnoDB to {file}")

