@extends('layouts.app')
@section('title')
    Map
@endsection
@section('content')
    <style>
        #mapid {
            height: 91vh;
        }

        span {
            cursor: pointer;
        }
    </style>
    {{-- declation part --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var objPins = [], objPaths = [];
        var marks = [], polylines = [];
        var isAuth = false;

        @auth
            isAuth = true
        @else
            isAuth = false

        @endauth

        function setPins(lati, long, desc) {
            $.ajax({
                url: '/pins/',
                type: 'POST',
                data: {lati: lati, long: long, desc: desc}
            })
        }

        function updatePinsInDB(id, lati, long, desc) {
            $.ajax({
                url: '/pins/' + id,
                type: 'PUT',
                data: {lati: lati, long: long, desc: desc}
            })
        }

        function deletePin(id) {
            objPins = objPins.filter(e => {e.id != id})
            $.ajax({
                url: '/pins/' + id,
                type: 'DELETE'
            })
            if (objPins.length===0)
                $.ajax({
                    url: '/pins/reset/',
                    type: 'GET'
                })
        }

        function setPath(lati1, long1, lati2, long2) {
            $.ajax({
                url: '/paths/',
                type: 'POST',
                data: {
                    lati1: lati1, long1: long1,
                    lati2: lati2, long2: long2
                }
            });
        }

        function deletePath(id) {
            objPaths = objPaths.filter(e => {e.id != id})
            $.ajax({
                url: '/paths/' + id,
                type: 'DELETE'
            })
            if (objPaths.length===0)
                $.ajax({
                    url: '/paths/reset/',
                    type: 'GET'
                })
        }

        function initPins() {
            @if(count($pins)!=0)
            @foreach($pins as $pin)
            objPins.push({
                id: {{$pin->id}},
                lati: {{$pin->lati}},
                long: {{$pin->long}},
                desc: "{{$pin->desc}}"
            })
            makePin({{$pin->id}}, {{$pin->lati}},
                {{$pin->long}}, "{{$pin->desc}}")
            @endforeach
            @endif
        }

        function initPaths() {
            @if(count($paths)!=0)
            @foreach($paths as $path)
            makePath({{$path->id}},
                [{{$path->lati1}}, {{$path->long1}}],
                [{{$path->lati2}}, {{$path->long2}}])
            @endforeach
            @endif
        }

        function makePin(id, lati, long, desc) {
            const mark = L.marker([lati, long]).bindPopup(desc)
            marks.push({id: id, mark: mark})
            L.layerGroup([mark]).addTo(mymap);

            const item = document.createElement('li');
            item.setAttribute('id', "span" + id)
            item.setAttribute('class', "list-group-item d-flex align-items-center")

            if (isAuth)
                item.innerHTML = `<span class="material-icons">place</span>${desc}
            <span id="span_e${id}" class="material-icons">edit</span>
            <span id="span_rc${id}" class="material-icons">remove_circle_outline</span>`
            else
                item.innerHTML = `<span class="material-icons">place</span>${desc}`


            sideOptions.appendChild(item)
        }

        function makePath(id, t1, t2) {
            objPaths.push({
                id: id,
                start: [t1[0], t1[1]],
                end: [t2[0], t2[1]],
            })

            var popupContent = `<div class=popup>
<span id="s${id}" class="material-icons">undo</span>
@auth<span id="r${id}" class="material-icons">remove_circle</span>@endauth
            <span id="e${id}" class="material-icons">redo</span></div>`;

            polylines.push({
                id: id,
                layer:
                    L.polyline([
                            t1,
                            t2,
                        ]
                    ).on('mouseover', function (e) {
                        var layer = e.target;
                        layer.setStyle({
                            color: '#3388ff',
                            weight: 5
                        });
                    }).on('mouseout', function (e) {
                        var layer = e.target;
                        layer.setStyle({
                            color: '#3388ff',
                            weight: 3
                        });
                    }).on('click', function (e) {
                        var layer = e.target;
                        layer.setStyle({
                            color: "orange",
                            weight: 4
                        });
                    }).bindPopup(popupContent).addTo(mymap)
            })
        }
    </script>
    <div class="row d-flex flex-row-reverse">
        <div id="mapid" class="col-md-10 ml-0 pl-0"></div>
        <script>
            "use strict";
            const API_Key = "pk.eyJ1Ijoic3BsaW10ZXIiLCJhIjoiY2s5dTgzeHkwMDByZTNocGc4aWcwMXMzbSJ9.4ncq--3gDyZIP1TbkNohqg";
            const mymap = L.map('mapid').setView([36.2238429, 1.2405443], 16.25);

            L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18, id: 'mapbox/streets-v11',
                tileSize: 512, zoomOffset: -1, accessToken: API_Key
            }).addTo(mymap);


            let mode = "", chk = 0, t1 = [], t2 = [], desc;
            // e.latlng.lat, e.latlng.lng
            mymap.on('click', function (e) {
                console.log("chk", chk)
                if (mode === 'path')
                    if (chk === 0) {
                        t1 = [e.latlng.lat, e.latlng.lng]
                        chk++;
                    } else if (chk === 1) {
                        t2 = [e.latlng.lat, e.latlng.lng]
                        if (objPaths.length === 0)
                            makePath(1, t1, t2)
                        else
                            makePath(objPaths[objPaths.length-1].id+1, t1, t2)
                        chk = 0
                        mode = ""
                        setPath(t1[0], t1[1], t2[0], t2[1])
                    }
                if (mode === 'waypoint') {
                    makePin(e.latlng.lat + e.latlng.lng, e.latlng.lat, e.latlng.lng, desc)
                    mode = ""
                    setPins(e.latlng.lat, e.latlng.lng, desc)
                }
            });

        </script>
        <div id="sideOptionsContainer" class="col-md-2 pr-0 mr-0">
            <ul id="sideOptions" class="list-group"></ul>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pin</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" novalidate>
                        <label for="txt_desc">City</label>
                        <input type="text" class="form-control" id="txt_desc" required>
                        <div class="invalid-feedback">
                            Please provide a valid city.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="btn_submit" type="submit" class="btn btn-primary" disabled data-dismiss="modal">Add
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- End Modal -->
    <script>
        initPins()
        initPaths()

        $('li').on('click', (evnt) => {
            objPins.map(e => {
                if (e.id == evnt.target.id) {
                    console.log(evnt.target.id)
                    mymap.setView([e.lati, e.long], 16.25);
                }
            })
        });
    </script>
    <script src="{{ asset('js/handler.js') }}" type="application/javascript"></script>
@endsection
