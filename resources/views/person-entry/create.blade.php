<x-app-layout :title="__('Control Access')">
    <x-slot name="header">
        <h2 class="font-semibold text-3xl custom-gradient-text">
            {{ __('New Entry') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg dark:bg-gray-800 rounded-lg">
                <div class="text-gray-800 dark:text-gray-100 pb-8">
                    <form id="person-entry-form" action="{{ route('person-entries.store') }}" method="post">
                        @include('person-entry.create-form-fields')
                        <x-primary-button type="submit" class="ml-12">{{ __('Save') }}</x-primary-button>
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("person-entry-form");
            form.addEventListener("submit", function (event) {
                event.preventDefault(); // Evitar la recarga de página

                const formData = new FormData(form);
                fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.pdfUrl) {
                            window.open(data.pdfUrl, '_blank'); // Abre el PDF en una nueva pestaña
                        }
                        window.location.href = "{{ route('control-access') }}"; // Redirigir después del submit
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    </script>
</x-app-layout>
