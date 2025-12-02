@extends('backend.system.layouts.master')
@section('header')
    <script type="text/javascript" src='https://code.jquery.com/jquery-3.7.1.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
            integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{asset('assets/plugin/drag-drop-menu/jquery-menu-editor.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('assets/plugin/drag-drop-menu/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('assets/plugin/drag-drop-menu/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js')}}"></script>

    <script>
        jQuery(document).ready(function () {
            var arrayjson = @json($thisData->menu_json ?? old('menu_json'));

             arrayjson = (arrayjson!==null) ? JSON.parse(arrayjson) : [{
                "href": "http://home.com",
                "icon": "fas fa-home",
                "text": "Home",
                "target": "_top",
                "title": "Home Sample"
            }, {
                "icon": "fas fa-chart-bar",
                "text": "Team"
            }, {
                "icon": "fas fa-bell",
                "text": "Testimonial"
            }, {
                "icon": "fas fa-crop",
                "text": "Contact Us"
            }, {
                "icon": "fas fa-flask",
                "text": "Blogs"
            }, {
                "icon": "fas fa-map-marker",
                "text": "Pages"
            }, {
                "icon": "fas fa-search",
                "text": "Categories",
                "children": [{
                    "icon": "fas fa-plug",
                    "text": "Category 1",
                    "children": [{
                        "icon": "fas fa-filter",
                        "text": "Category-1-1"
                    }]
                }]
            }];

            var iconPickerOptions = {searchText: "Buscar...", labelHeader: "{0}/{1}"};
            var sortableListOptions = {
                placeholderCss: {'background-color': "#cccccc"}
            };
            var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions});
            editor.setForm($('#frmEdit'));
            editor.setUpdateButton($('#btnUpdate'));
            $('#btnReload').on('click', function () {
                editor.setData(arrayjson);
                var str = editor.getString();
                $("#out").text(str);
            });
            $('#btnOutput').on('click', function () {
                var str = editor.getString();
                $("#out").text(str);
            });

            $("#btnUpdate").click(function () {
                editor.update();
                var str = editor.getString();
                $("#out").text(str);
            });

            $('#btnAdd').click(function () {
                editor.add();
                var str = editor.getString();
                $("#out").text(str);
            });
            /* ====================================== */

            /** PAGE ELEMENTS **/
            $('[data-toggle="tooltip"]').tooltip();
            // $(document).on("mousemove", function (event) {
            //     $('#btnReload').click();
            //     if (event.button === 0) { // Check for left mouse button
            //         var str = editor.getString(); // Get the string from the editor
            //         $("#out").text(str); // Set the text of the #out element
            //     }
            // });
            const btnSave = document.getElementById('btnSave');

            // Add event listener for mouse hover (mouseover)
            btnSave.addEventListener('mouseover', function() {
                // Action when mouse hovers over btnSave
                var str = editor.getString(); // Get the string from the editor
                $("#out").text(str); // Set the text of the #out element
                 });

            $('#btnReload').click();
        });
    </script>
@endsection
@section('content')
    <div class="container">
        @include('backend.system.partials.errors')
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header"><h5 class="float-start">Menu</h5>
                        <div class="float-end">
                            <button id="btnReload" type="button" class="btn btn-outline-secondary">
                                <i class="fa fa-play" style="margin-right: 5px;"></i> Load Data
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul id="myEditor" class="sortableLists list-group">
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <form method="post" action="{{route('menus.update',$thisData->id)}}">
                        @csrf
                        @method('put')
                        <div class="card-header">JSON Output
                            <div class="float-end">
                                <button id="btnOutput" type="button" class="btn btn-success"><i
                                        class="fas fa-check-square" style="margin-right: 5px;"></i> Update Menu
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <textarea id="out" class="form-control" cols="50" name="menu_json"
                                                              rows="10">{{$thisData->menu_json??old('menu_json')}}</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="btnSave" class="btn btn-primary"><i
                                    class="fas fa-save" style="margin-right: 2px;"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-primary mb-3">
                    <div class="card-header bg-primary text-white">Edit item</div>
                    <div class="card-body">
                        <form id="frmEdit" class="form-horizontal">
                            <div class="form-group mb-3">
                                <label for="text" class="form-label">Text</label>
                                <div class="input-group">
                                    <input type="text" required class="form-control item-menu" name="text" id="text"
                                           placeholder="Text">
                                    <button type="button" id="myEditor_icon" class="btn btn-outline-secondary"></button>
                                </div>
                                <input type="hidden" name="icon" class="item-menu">
                            </div>
                            <div class="form-group mb-3">
                                <label for="href"  class="form-label">URL</label>
                                <input type="text" required class="form-control item-menu" id="href" name="href"
                                       placeholder="URL">
                            </div>
                            <div class="form-group mb-3">
                                <label for="target" class="form-label">Target</label>
                                <select name="target" id="target" class="form-control item-menu">
                                    <option value="_self">Self</option>
                                    <option value="_blank">Blank</option>
                                    <option value="_top">Top</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="title" class="form-label">Tooltip</label>
                                <input type="text" name="title" class="form-control item-menu" id="title"
                                       placeholder="Tooltip">
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i class="fas fa-sync-alt"
                                                                                                 style="margin-right: 2px;"></i>
                            Update
                        </button>
                        <button type="button" id="btnAdd" class="btn btn-success"><i class="fas fa-plus"
                                                                                     style="margin-right: 2px;"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
