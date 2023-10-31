<x-mail::message>
# {{$name}}, your new UKPID account is ready.

## Your account credentials
* **Email address:** {{$email}}
* **Temporary password:** {{$password}}

## Here's what to do next
* Sign in to UKPID using these credentials
* Once you've signed in, you'll be asked to create your own password

<x-mail::button :url="$url">
    Open UKPID
</x-mail::button>
</x-mail::message>
