<x-layout>
    <div class="container">
        <br><br><br>
            @isset($Err)
                <h5>
                    {{ $Err }}
                </h5>
            @endisset
        <br><br><br>
    </div>
</x-layout>