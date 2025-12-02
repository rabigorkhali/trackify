@extends('backend.system.layouts.master')


@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12" id="fm-main-block">
                <x-pulse>
                    <livewire:pulse.servers cols="full"/>

                    <livewire:pulse.usage cols="4" rows="2"/>

                    <livewire:pulse.queues cols="4"/>

                    <livewire:pulse.cache cols="4"/>

                    <livewire:pulse.slow-queries cols="8"/>

                    <livewire:pulse.exceptions cols="6"/>

                    <livewire:pulse.slow-requests cols="6"/>

                    <livewire:pulse.slow-jobs cols="6"/>

                    <livewire:pulse.slow-outgoing-requests cols="6"/>
                </x-pulse>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function () {
            jQuery(".ml-2.text-lg.sm\\:text-2xl.text-gray-700.dark\\:text-gray-300.font-medium").empty();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const element = document.querySelector(".bg-gray-50.dark\\:bg-gray-950");
            if (element) {
                element.classList.replace("dark:bg-gray-950", "dark:bg-white-950");
            }
        });
    </script>
@endsection
