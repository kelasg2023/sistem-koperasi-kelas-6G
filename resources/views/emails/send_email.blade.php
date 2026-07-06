<x-mail::message>
# {{ $mailSubject }}

Halo,

{{ $mailContent }}

Terima kasih,<br>
Tim {{ config('app.name', 'Sistem Koperasi 6G') }}
</x-mail::message>
