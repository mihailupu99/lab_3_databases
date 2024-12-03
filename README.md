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

# Partea Laborator Nr. 4. Formulare și validarea datelor

## №2. Crearea formularului

Formular creat cu ajutorul VUE

```vue
<template>
    <div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Create Task</h1>
        <form @submit.prevent="submit" class="space-y-6">
            <!-- Title -->
            <div>
                <label
                    for="title"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Title</label
                >
                <input
                    v-model="form.title"
                    type="text"
                    id="title"
                    class="input mt-1 block w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter task title"
                    required
                />
            </div>

            <!-- Description -->
            <div>
                <label
                    for="description"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Description</label
                >
                <textarea
                    v-model="form.description"
                    id="description"
                    rows="4"
                    class="input mt-1 block w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter task description"
                ></textarea>
            </div>

            <!-- Category -->
            <div>
                <label
                    for="category"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Category</label
                >
                <select
                    v-model="form.category_id"
                    id="category"
                    class="input mt-1 block w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option value="" disabled>Select a category</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <!-- Tags -->
            <div>
                <label
                    for="tags"
                    class="block text-sm font-medium text-gray-700 mb-1"
                    >Tags</label
                >
                <select
                    v-model="form.tags"
                    id="tags"
                    multiple
                    class="input mt-1 block w-full p-3 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                >
                    <option v-for="tag in tags" :key="tag.id" :value="tag.id">
                        {{ tag.name }}
                    </option>
                </select>
                <p class="text-sm text-gray-500 mt-1">
                    Hold <strong>Ctrl</strong> (or <strong>Cmd</strong> on Mac)
                    to select multiple tags.
                </p>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button
                    type="submit"
                    class="btn btn-primary bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50"
                >
                    Save Task
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import { useForm } from "@inertiajs/vue3";

export default {
    layout: DefaultLayout,
    props: {
        categories: Array, // List of categories passed from the server
        tags: Array, // List of tags passed from the server
    },
    setup() {
        const form = useForm({
            title: "",
            description: "",
            category_id: null, // Category ID
            tags: [], // Selected tags
        });

        function submit() {
            form.post("/tasks");
        }

        return { form, submit };
    },
};
</script>

<style scoped>
.input {
    transition: all 0.3s ease-in-out;
}

.input:focus {
    outline: none;
}
</style>
```

## №3. Validarea datelor pe partea de server

Exemplu de validare a datelor.

```php
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

```

## №4. Crearea unei clase de cerere personalizată (Request)

Cream Requestl CreateTaskRequest

```php
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Titlul este obligatoriu.',
            'title.string' => 'Titlul trebuie să fie un șir de caractere.',
            'title.min' => 'Titlul trebuie sa aiba mai mult de 2 caractere.',
            'title.max' => 'Titlul nu poate avea mai mult de 255 de caractere.',
            'description.string' => 'Descrierea trebuie să fie un șir de caractere.',
            'category_id.exists' => 'Categoria selectată nu este validă.',
            'tags.array' => 'Tag-urile trebuie să fie un array.',
            'tags.*.exists' => 'Unul sau mai multe tag-uri selectate nu sunt valide.',
        ];
    }

```

TaskController foloseste CreateTaskRequest pentru validare

```php
   public function store(CreateTaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create($validated);

        if (isset($validated['tags'])) {
            $task->tags()->attach($validated['tags']);
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

```

## №5. Adăugarea mesajelor flash

Pentru mesaj flash de confirmare la salvarea cu succes a sarcinii am folosit vue-toastification

```javascript

function submit() { form.post("/tasks"); toast.success("Task added
succesfully"); }

```

## №6. Protecția împotriva CSRF

Deoarece am folosti Vue si inertiajs pentru handling la requesturi, csrf_token se include automat.

"You can use Inertia's shared data functionality to automatically include the csrf_token with each response."

Iar in alte parti unde nu am folosit inertia ci axios, acesta tot are CSRF built in.

## №7. Actualizarea sarcinii

```vue
<template>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Task</h1>

        <form @submit.prevent="submit">
            <!-- Your existing form fields -->

            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium"
                    >Title</label
                >
                <input
                    v-model="form.title"
                    type="text"
                    id="title"
                    class="w-full mt-2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium"
                    >Description</label
                >
                <textarea
                    v-model="form.description"
                    id="description"
                    rows="4"
                    class="w-full mt-2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                ></textarea>
            </div>

            <div class="mb-4">
                <label for="category" class="block text-gray-700 font-medium"
                    >Category</label
                >
                <select
                    v-model="form.category_id"
                    id="category"
                    class="w-full mt-2 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    <option value="">No category</option>
                    <option
                        v-for="category in categories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label for="tags" class="block text-gray-700 font-medium"
                    >Tags</label
                >
                <div class="flex flex-wrap gap-2 mt-2">
                    <span
                        v-for="tag in allTags"
                        :key="tag.id"
                        class="px-2 py-1 border rounded-lg cursor-pointer"
                        :class="{
                            'bg-teal-600 text-white': form.tags.includes(
                                tag.id
                            ),
                            'bg-gray-200 text-gray-800': !form.tags.includes(
                                tag.id
                            ),
                        }"
                        @click="toggleTag(tag.id)"
                    >
                        {{ tag.name }}
                    </span>
                </div>
            </div>
            <!-- Add error messages -->
            <div v-if="form.errors.title" class="text-red-500 mt-1">
                {{ form.errors.title }}
            </div>

            <!-- Add loading state -->
            <button
                type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 disabled:opacity-50"
                :disabled="form.processing"
            >
                {{ form.processing ? "Saving..." : "Save Changes" }}
            </button>
        </form>
    </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";

export default {
    props: {
        task: Object,
        categories: Array,
        allTags: Array, // Add this prop
    },
    setup(props) {
        const form = useForm({
            title: props.task.title,
            description: props.task.description,
            category_id: props.task.category_id || "",
            tags: props.task.tags.map((tag) => tag.id) || [],
        });

        const toggleTag = (tagId) => {
            const index = form.tags.indexOf(tagId);
            if (index > -1) {
                form.tags.splice(index, 1);
            } else {
                form.tags.push(tagId);
            }
        };

        const submit = () => {
            form.put(route("tasks.update", props.task.id), {
                onSuccess: () => {
                    // Optional: Add success handling
                },
                preserveScroll: true,
            });
        };

        return { form, toggleTag, submit };
    },
};
</script>
```

Metoda update din controller

```php
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

```

# Partea Laborator Nr. 5. Componentele de securitate în Laravel

## Nr. 1. Pregătirea pentru lucru

Am folosit proiectul din lucrarile de laborator precedente.

## Nr. 2. Autentificarea utilizatorilor

Implementarea AuthController cu functiile de register, login si logout.

```php
class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        sleep(1);
        // Validate
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],

        ]);

        // Register
        $user = User::create($fields);

        // Login
        Auth::login($user);

        // Redirect
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($fields, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}


```

## Nr. 3. Autentificarea utilizatorilor cu ajutorul componentelor existente

Utilizarea bibliotecei Laravel Breeze care creaza singura diverse controllere de tip Auth

Exemplu RegisteredUserController

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}


```

Exemplu rute

```php
Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

```

## Nr. 4. Autorizarea utilizatorilor

Rute protejate prin auth

```php
Route::middleware('auth')->group(function () {
    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
    // Route::inertia('/tasks', 'Tasks')->name('tasks');
    Route::resource('tasks', TaskController::class);

    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');


    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


```

## Nr. 6. Deconectarea și protecția împotriva CSRF

Functia logout

```php
   public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

```

Link ce face point spre logout

```vue
<Link
    :href="route('logout')"
    method="post"
    as="button"
    type="button"
    class="px-4 py-2 rounded-xl hover:bg-gray-400 transition hover:text-white"
>Logout</Link>
```

By default vue si anume inertia au protectie impotriva CSRF
