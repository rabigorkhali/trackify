{{--<script src="{{ asset('/assets/vendor/libs/jquery/jquery.js') }}"></script>--}}
<script src="{{ asset('/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
<script src="{{ asset('/assets/vendor/js/menu.js') }}"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/swiper/swiper.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@if(file_exists(public_path('/assets/vendor/libs/sortablejs/sortable.js')))
<script src="{{ asset('/assets/vendor/libs/sortablejs/sortable.js') }}"></script>
@else
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endif

<!-- Main JS -->
<script src="{{ asset('/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ asset('/assets/js/dashboards-analytics.js') }}"></script>

<script src="{{ asset('/compiled/js/system.js') }}"></script>
<script>
    $(document).ready(function () {
        // Initialize CKEditor for all textareas with the class 'editor'
        document.querySelectorAll('.text-editor').forEach((textarea) => {
            CKEDITOR.replace(textarea.id, {
                allowedContent: true,
                filebrowserUploadUrl: "{{ route('ckeditor.upload') }}?_token={{ csrf_token() }}",
                filebrowserUploadMethod: 'form',
                imageUploadUrl: "{{ route('ckeditor.upload') }}?_token={{ csrf_token() }}", // Enable direct image pasting
                on: {
                    fileUploadResponse: function(evt) {
                        console.log('Upload Response:', evt.data.fileLoader.xhr.responseText);
                    }
                }
            });
        });
        setTimeout(function () {
            $('.cke_notifications_area').hide();
        }, 500); // 3000 milliseconds = 3 seconds
    });

    $(document).ready(function () {
        $('.select2').select2();
        $('.select2-multiple').select2({
            tags: true,
            placeholder: 'Select or Type',
            tokenSeparators: [',', ' '],
            allowClear: true
        });
        $('#select-all').on('click', function () {
            var $select = $('.select2-multiple');
            var allValues = [];

            // Gather all existing option values from the select element
            $select.find('option').each(function () {
                allValues.push($(this).val());
            });

            // Update the selection
            $select.val(allValues).trigger('change');
        });
    });
    $(document).ready(function () {
        $('#title').on('input', function () {
            let title = $(this).val();
            let slug = slugify(title);
            $('#slug').val(slug);
        });

        function slugify(text) {
            return text
                .toString()
                .toLowerCase()
                .trim()
                .replace(/\s+/g, '-')        // Replace spaces with -
                .replace(/[^\w\-]+/g, '')    // Remove all non-word chars
                .replace(/\-\-+/g, '-')      // Replace multiple - with single -
                .replace(/^-+/, '')          // Trim - from start of text
                .replace(/-+$/, '');         // Trim - from end of text
        }
    });

</script>

@yield('scripts')
