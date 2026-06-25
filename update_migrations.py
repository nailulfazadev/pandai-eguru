import glob
import os

classrooms_up = """    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('subject');
            $table->timestamps();
        });
    }"""

students_up = """    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('nisn')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->timestamps();
        });
    }"""

schedules_up = """    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->string('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }"""

journals_up = """    public function up(): void
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }"""

attendances_up = """    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }"""

mapping = {
    'create_classrooms_table': classrooms_up,
    'create_students_table': students_up,
    'create_schedules_table': schedules_up,
    'create_journals_table': journals_up,
    'create_attendances_table': attendances_up
}

for file_path in glob.glob('database/migrations/*.php'):
    for key, replacement in mapping.items():
        if key in file_path:
            with open(file_path, 'r') as f:
                content = f.read()
            
            # Find the up method
            import re
            content = re.sub(r'    public function up\(\): void\n    \{\n        Schema::create\(.*?\n        \}\);\n    \}', replacement, content, flags=re.DOTALL)
            
            with open(file_path, 'w') as f:
                f.write(content)
            print(f"Updated {file_path}")

