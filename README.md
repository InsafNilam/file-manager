# Laravel Inertia React File Management Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravelguru/laravel-filehandler.svg?style=flat-square)](https://packagist.org/packages/laravelguru/laravel-filehandler)
[![Total Downloads](https://img.shields.io/packagist/dt/laravelguru/laravel-filehandler.svg?style=flat-square)](https://packagist.org/packages/laravelguru/laravel-filehandler)
![GitHub Actions](https://github.com/InsafNilam/file-manager/actions/workflows/main.yml/badge.svg)

## Overview

The Laravel Inertia React File Management package provides seamless file management capabilities for your Laravel applications using React and Inertia.js. It includes prebuilt file input components and popup file dialogs for easy file uploads, storage, browsing, and management.

# Features

- Seamless integration with React and Inertia.js
- Utilizes ShadCN components for dialogs, buttons, scroll areas, and tabs
- Prebuilt file input components and popup file dialogs
- Smooth single-page application (SPA) transitions
- Comprehensive file operations: upload, download, delete, move files
- Responsive file browser
- Ideal for CMS, e-commerce platforms, project management tools, and personal portfolios

## Installation

You can install the package via composer:

```bash
composer require laravelguru/laravel-filehandler
```

# Register Service Provider

If you are using laravel 11 or update version you should add the service provider into bootstrap/providers.php:

```bash
<?php

return [
    // Other Service Providers
    LaravelGuru\LaravelFilehandler\ServiceProvider::class,
];
```

## Publishing Assets

Run the following commands to publish the package assets:

- This package is designed to be loaded only when the application is running in a web environment, ensuring it's not unnecessarily loaded during command-line operations. By checking if the app is running in the console using app()->runningInConsole();

```bash
    php artisan serve
    npm run dev
```

- The package optimizes performance by activating only when needed for web requests, keeping the application lightweight during CLI operations.

**please know the use case before publishing the resources**

- If you don't use pre-built components, just migration is enough
- If you don't use pre-built components, doesn't require modifications you might need migration, model, resource, components
- Otherwise, you need to require everything

```bash
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider"

or

php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=filehandler-config
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=filehandler-migration
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=filehandler-controller
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=filehandler-resource
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=filehandler-model
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=filehandler-components
php artisan vendor:publish --provider="LaravelGuru\LaravelFilehandler\ServiceProvider" --tag=filehandler-css
```

## Link up the Storage of the Laravel

```bash
php artisan storage:link
```

## Install shadcn/ui Components (only if predefined components are used)

Initialize the shadcn/ui components by following the [official installation guide](https://ui.shadcn.com/docs/installation/laravel). Use the commands below to add the required components:

```bash
npx shadcn-ui@latest add button
npx shadcn-ui@latest add dialog
npx shadcn-ui@latest add tabs
npx shadcn-ui@latest add scroll-area
```

## Setup

- Install Dependencies: Ensure you have React and Inertia.js set up in your Laravel project.
- Integrate Components: Use the provided React components and Inertia.js middleware in your application.
- Customize: Modify the components and handlers as needed to fit your requirements.
- Run Migrations: Apply the migrations to your database:

# Run Migration

The service provider will automatically generate a migration for the file_repos table when the application boots. Run the migration using:

```bash
php artisan migrate
```

## Usage

While views/components can be optional for API development, integrating them can enhance user experience. This package focuses on providing core API routes for index, show, store, update, and delete operations. If you're primarily handling file management, this package can serve as a solid foundation.

```bash
    public function __construct(FileService $fileService)
```

- **Description:** Constructor method that injects the FileService class, making it available to the FileController class.

```bash
    public function index()
```

**Description:** Retrieves and returns a paginated list of files associated with the authenticated user within the 'documents' folder.

```bash
    public function store(Request $request)
```

**Description:** Handles the uploading of new files. It receives the files from the request, processes the upload, and returns a JSON response indicating the success or failure of the operation.

```bash
    private function upload($user_id, $files)
```

**Description:** A private method that performs the actual upload of files to storage and updates the database within a transaction. It returns the uploaded files' data.

```bash
    public function show(File $file)
```

**Description:** Retrieves and returns the details of a specific file.

```bash
    public function update(Request $request, File $file)
```

**Description:** Updates an existing file with new data. It processes the file modification and returns a JSON response indicating the success or failure of the operation.

```bash
    public function destroy(File $file)
```

**Description:** Deletes a specified file and returns a JSON response indicating the success or failure of the deletion operation.

## Leverage pre-built components for efficient development.

This package offers ready-to-use file input and multiple file handling components built on top of Shadcn UI. Streamline your development process with pre-designed, customizable elements. Enjoy enhanced user experience and rapid prototyping.

### FileInput

#### Handle Multiple Files

- Validation Rules Configuration

```php
    $data = $request->validate([
        "cv_path" => ["nullable", "integer", "exists:files,id"],
        "brochures" => ["nullable", "array", "max:5"],
        "brochures.*" => ["nullable", "integer", "exists:files,id"],
    ])
```

- Database Migration Setup

```php
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->string('brochures')->nullable();
        });
    }
```

- Controller Method for the Create Function
  (**Ensure Focus on the documents variable during File Synchronization in Your Views**)

```php
public function create()
{
    $faculties = Faculty::query()->orderBy('name', 'asc')->get();
    $documents = File::query()->where('user_id', auth()->id())->where('folder', 'documents')->paginate(9)->onEachSide(1);

    return Inertia::render('Courses/Create', [
        'documents' => FileResource::collection($documents),
        'faculties' => FacultyResource::collection($faculties),
    ]);
}
```

- Guide to Using File Input for File Synchronization in your Create View
  (**Ensure Focus on the documents variable + brochures during File Synchronization in Your Views**)

```JSX
export default function Create({ auth, documents, faculties }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        brochures: null,
    });

    const [files, setFiles] = useState([]);
    const [submitTriggered, setSubmitTriggered] = useState(false);

    const onSubmit = (e) => {
        e.preventDefault();

        if (files.length === 0) {
        post(route("courses.store"), {
            onSuccess: () => {
                reset();
            },
            onError: () => {
                console.log("Error creating course");
            },
        });
        return;
        }

        setData(
            "brochures",
            files.map((file) => file.id)
        );

        setSubmitTriggered(true);
    };

    useEffect(() => {
        if (submitTriggered) {
        post(route("courses.store"), {
            onSuccess: () => {
                reset();
                setSubmitTriggered(false);
            },
            onError: () => {
                console.log("Error creating course");
                setSubmitTriggered(false);
            },
        });
        }
    }, [submitTriggered]);

    return (
        <div className="grid gap-2">
            <Label htmlFor="name">Brochures</Label>
            <div className="w-full overflow-x-auto">
                <FileInput
                    selectedFiles={files}
                    onFileChange={(files) => {
                        setFiles(files);
                    }}
                    apiUrl={route("files.store")}
                    multiple={true}
                    documents={documents}
                />
            </div>
        </div>
    )
}
```

- Controller Method for the Store Function
  (**Ensure Focus on the brochures variable during File Store in respective database table**)

```php
    public function store(StoreCourseRequest $request)
        {
            $data = $request->validated();

            if (isset($data['brochures'])) {
                $data['brochures'] = json_encode($data['brochures'], true);
            }

            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();

            if (Gate::allows('create_course')) {
                Course::create($data);
                return redirect()->route('courses.index')->with('success', 'Course created successfully');
            } else {
                return redirect()->back()->with('error', 'You are not authorized to create a course');
            }
        }
```

- Controller Method for the Edit Function
  (**Ensure Focus on the documents variable + brochures during File Synchronization in Your Views**)

```php
    public function edit(Course $course)
    {
        if (Gate::allows('update_course', $course)) {
            $faculties = Faculty::query()->orderBy('name', 'asc')->get();
            $documents = File::query()->where('user_id', auth()->id())->where('folder', 'documents')->paginate(9)->onEachSide(1);

            $array = json_decode($course->brochures) ?? [];
            $brochures = File::whereIn('id', $array)->get();

        return Inertia::render('Courses/Edit', [
            'course' => new CourseResource($course),
            'brochures' => FileResource::collection($brochures),
            'documents' => FileResource::collection($documents),
            'faculties' => FacultyResource::collection($faculties),
        ]);
        } else {
        return redirect()->back()->with('error', 'You are not authorized to edit this course');
        }
    }
```

- Guide to Using File Input for File Synchronization in your Edit View
  (**Ensure Focus on the documents variable + brochures during File Synchronization in Your Views**)

```JSX
export default function Edit({auth, course, brochures, documents, faculties }){
    const { data, setData, put, processing, errors, reset } = useForm({
        brochures: null,
    })

    const [files, setFiles] = useState(brochures.data ?? []);

    const onSubmit = (e) => {
        e.preventDefault();
        if (files.length === 0) {
            put(route("courses.update", course.id), {
                preserveScroll: true,
                onSuccess: () => {
                reset();
                },
                onError: () => {
                console.log("Error updating course");
                },
            });
        return;
        }

    setData(
      "brochures",
      files.map((file) => file.id)
    );

    setSubmitTriggered(true);
  };

    useEffect(() => {
        if (submitTriggered) {
        put(route("courses.update", course.id), {
            onSuccess: () => {
            reset();
            setSubmitTriggered(false);
            },
            onError: () => {
            console.log("Error updating course");
            setSubmitTriggered(false);
            },
        });
        }
    }, [submitTriggered]);

    return(
        <div className="grid gap-2">
            <Label htmlFor="name">Brochures</Label>
            <div className="w-full overflow-x-auto">
                <FileInput
                selectedFiles={files}
                onFileChange={(files) => {
                    setFiles(files);
                }}
                apiUrl={route("files.store")}
                multiple={true}
                documents={documents}
                />
            </div>
        </div>
        )
    }
```

- Controller Method for the Update Function
  (**Ensure Focus on the brochures variable during File Update in respective database table**)

```php
    public function update(UpdateCourseRequest $request, Course $course)
    {
    //
        $data = $request->validated();

        if (isset($data['brochures'])) {
        $data['brochures'] = json_encode($data['brochures'], true);
        }

        $data['updated_by'] = auth()->id();

        if (Gate::allows('update_course', $course)) {
        $course->update($data);
        return redirect()->route('courses.index')->with('success', 'Course updated successfully');
        } else {
        return redirect()->back()->with('error', 'You are not authorized to update this course');
        }
    }
```

#### Handle Single File

- Validation Rules Configuration

```php
    $data = $request->validate([
        "cv_path" => ["nullable", "integer", "exists:files,id"],
    ])
```

- Database Migration Setup

```php
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('cv_path')->nullable();
        });
    }
```

- Controller Method for the Edit Function
  (**Ensure Focus on the cv_path and documents variable during File Edit during File Synchronization in Your Views**)

```php
  public function edit(User $user)
  {
        $documents = File::query()->where('user_id', auth()->id())->where('folder', 'documents')->paginate(9)->onEachSide(1);

        $cv_array = [json_decode($user->cv_path)] ?? [];
        $cv_path = [];

        if (is_array($cv_array) && count($cv_array) > 0) {
            $cv_path = File::whereIn('id', $cv_array)->get();
        }

        return Inertia::render('User/Edit', [
          'user' => new UserResource($user),
          'cv_path' => FileResource::collection($cv_path),
          'documents' => FileResource::collection($documents),
        ]);
    }
```

- Guide to Using File Input for File Synchronization in your Edit View
  (**Ensure Focus on the cv_path variable + brochures during File Synchronization in Your Views**)

```JSX
export default function EditStaff({ auth, user, cv_path, documents }) {
    const { data, setData, put, processing, errors, reset } = useForm({
        cv_path: null
    });

    const [cvFile, setCvFile] = useState(cv_path.data ?? []);

    return (
        <div className="grid gap-2">
            <Label htmlFor="cv_path">Upload CV</Label>
            <div className="w-full overflow-x-auto">
                <FileInput
                    selectedFiles={cvFile}
                    onFileChange={(files) => {
                        setCvFile(files);
                        setData("cv_path", files?.[0]?.id);
                    }}
                    apiUrl={route("files.store")}
                    multiple={false}
                    documents={documents}
                />
            </div>
        </div>
    )
}
```

#### Handle Show File

- Controller Method for the Show Function (multiple + single)
  (**Ensure Focus on the brochures variable during File Show during File Synchronization in Your Views**)

```php
    public function show(Course $course)
    {
        $modules = $course->modules()->orderBy('created_at', 'asc')
        ->paginate(10)
        ->onEachSide(1);

        $array = json_decode($course->brochures) ?? [];
        $brochures = File::whereIn('id', $array)->get();

        return Inertia::render('Courses/Show', [
            'course' => new CourseResource($course->loadCount('modules')),
            'brochures' => FileResource::collection($brochures),
            'modules' => ModuleResource::collection($modules),
        ]);
    }
```

- Guide to display files that used File Input for File Synchronization in your Show View
  (**Ensure Focus on the brochures variable during File Synchronization in Your Views**)

```JSX
export default function Show({ auth, course, brochures, modules }) {
    return(
        <div>
        {
            brochures.data.map((brochure) => (
                <div className="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                    <div className="flex w-0 flex-1 items-center">
                    <svg
                        className="h-5 w-5 flex-shrink-0 text-gray-400"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                        fillRule="evenodd"
                        d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                        clipRule="evenodd"
                        />
                    </svg>
                    <div className="ml-4 flex min-w-0 flex-1 justify-between">
                        <span className="truncate font-medium hover:underline">
                        <a
                            href={brochure.path}
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            {brochure.name}
                        </a>
                        </span>
                        <span className="flex-shrink-0 text-gray-400">
                        2.4MB
                        </span>
                    </div>
                    </div>
                    <div className="ml-4 flex-shrink-0">
                    <a
                        href={brochure.path}
                        download
                        className="font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        Download
                    </a>
                    </div>
                </div>
            ))
        }
        </div>
    )
}
```

### FileDialog

- Validation Rules Configuration

```php
    $data = $request->validate([
        "image" => ["nullable", "string"],
    ])
```

- Database Migration Setup

```php
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->string('image')->nullable();
        });
    }
```

- Frontend Implementation

```JSX
    <div className="relative h-full w-80 bg-gray-100 dark:bg-gray-900">
        <img
        src={data.image ?? "https://via.placeholder.com/150"}
        alt="Course Image"
        className="w-full h-full object-cover ring-1 ring-gray-700 dark:ring-gray-300 p-1 object-center rounded-md"
        />
        <div className="absolute -bottom-5 -right-5">
        <FileDialog
            selectedFiles={selectedFiles}
            setSelectedFiles={(files) => {
            if (!files?.length) setData("image", null);
            setSelectedFiles(files);
            setData("image", files?.[0]?.path);
            }}
            multiple={false}
            apiUrl={route("files.store")}
            documents={documents}
        >
            <Button
            variant="outline"
            className="w-10 h-10 rounded-full shadow-md dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700 dark:text-gray-300"
            >
            <div>
                <Camera className="w-5 h-5 text-gray-500 dark:text-gray-400" />
            </div>
            </Button>
        </FileDialog>
        </div>
    </div>
```

The store and update methods using the File Dialog for models function similarly to how you handle other string variables in Laravel. For detailed guidance, please refer to the [Laravel Documentation](https://laravel.com/docs/11.x/readme).

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email insafnilam.2000@gmail.com instead of using the issue tracker.

## Credits

- [Insaf Nilam](https://github.com/laravelguru)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
