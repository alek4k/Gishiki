# CLI Toolkit
Gishiki uses a CLI toolkit to speed up development and code generation.

The executable resides under the ./vendor/bin/ directory, and is called
gishiki.

It accept an arbitrary number of arguments, but the first one is the action
you want to perform:

```sh
./vendor/bin/gishiki <action> [param 1] [param2] [param3] #and so on.....
```

The number of parameters following the action depends on the action you want to
perform.


## Application Creation
To bootstrap a new application the command is fixed:

```sh
./vendor/bin/gishiki new application
```

This will create a basic and empty application that uses an sqlite3 database,
has a randomly-generated RSA private key, and is ready to be executed.


## Controller Creation
To bootstrap a new controller the command requires controller name:

```sh
./vendor/bin/gishiki new controller ControllerName
```

This will create a basic controller with a small example piece of code.