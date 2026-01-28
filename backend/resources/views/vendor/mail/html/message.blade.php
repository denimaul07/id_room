<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
<!-- {{ config('app.name') }} -->
<!-- <img src="{{ storage_path('app/public/logo/mail.png') }}" style="height: 75px;width: 75px;"> -->
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} Imora Support System (ISS). @lang('All rights reserved.')
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
