# Lab 3 & 4 Framework.

## №1. Pregătirea pentru lucru

```

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=next_todoapp
DB_USERNAME=****pass****
DB_PASSWORD=****pass****


```

## №2. Crearea modelelor și migrațiilor

Definirea tabelei de categorii.

```php
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

Definirea tabelei task

```php
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};



```

Adaugarea campurilor fillable.

```php
class Tag extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_tag');
    }
}

```

## №3. Relația dintre tabele

Setarea cheilor straine si a legaturilor dintre tabele.

```php

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable();
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};

```

## №4. Relațiile dintre modele

Setarea relatiei din modelul Category

```php
class Category extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];


    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}


```

## №5. Crearea fabricilor și seed-urilor

Exemplu de factory pentru categories

```php
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->word, // Nume aleator
            'description' => $this->faker->sentence, // Descriere aleatoare
        ];
    }
}

```

## №6. Lucrul cu controlere și vizualizări

```php
class TaskController extends Controller
{
    //
    // Display a list of tasks
    public function index()
    {
        $tasks = Task::with('category', 'tags')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Show 10 items per page

        return inertia('Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }
    // Show a form for creating a new task
    public function create()
    {
        return Inertia::render('Tasks/Create', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    // Save a new task
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $task = Task::create($validated);

        if (isset($validated['tags'])) {
            $task->tags()->attach($validated['tags']);
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    // Display a single task
    public function show(Task $task)
    {
        $task->load(['category', 'tags']); // Eager load the category and tags relationships
        return Inertia::render('Tasks/Show', ['task' => $task,]);
    }

    // Show a form to edit a task
    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'task' => $task->load(['tags', 'category']),
            'categories' => Category::all(),
            'allTags' => Tag::all(),
        ]);
    }

    // Update the specified task
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Update the task
        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
        ]);

        // Sync tags
        if (isset($validated['tags'])) {
            $task->tags()->sync($validated['tags']);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }
    // Delete the specified task
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}


```
