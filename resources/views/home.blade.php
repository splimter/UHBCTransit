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

        .vertical-center {
            margin: 0;
            position: absolute;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
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
            console.log("opt");
            $.ajax({
                url: '/pins/',
                type: 'POST',
                data: {lati, long, desc, start, end}
            }).done(function(data,d) {
                makePin(data.id, lati, long, desc)
            })
            .fail(function(xhr, textStatus) {
                console.log("Request failed: " + textStatus);
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

        /*function setPath(lati1, long1, lati2, long2) {
            $.ajax({
                url: '/paths/',
                type: 'POST',
                data: {
                    lati1: lati1, long1: long1,
                    lati2: lati2, long2: long2
                }
            });
        }*/

        /*function deletePath(id) {
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
        }*/

        function initPins() {
            @if(count($pins)!=0)
            @foreach($pins as $pin)
            objPins.push({
                id: {{$pin->id}},
                lati: {{$pin->lati}},
                long: {{$pin->long}},
                desc: "{{$pin->desc}}"
            })
            makePin({{$pin->id}}, {{$pin->lati}}, {{$pin->long}}, "{{$pin->desc}}")
            @endforeach
            @endif
        }

        /*function initPaths() {
            @if(count($paths)!=0)
            @foreach($paths as $path)
            makePath({{$path->id}},
                [{{$path->lati1}}, {{$path->long1}}],
                [{{$path->lati2}}, {{$path->long2}}])
            @endforeach
            @endif
        }*/

        function makePin(id, lati, long, desc, start, end) {
           
            const mark = L.marker([lati, long]).bindPopup(desc)
            marks.push({id: id, mark: mark})
            L.layerGroup([mark]).addTo(mymap);
            
            if (isAuth){
            const item = document.createElement('li');
            item.setAttribute('id', "span" + id)
            item.setAttribute('class', "list-group-item d-flex justify-content-between pl-0 pr-0")

                item.innerHTML = `<span class="material-icons mr-1">place</span>${desc}
            <span id="span_e${id}" class="material-icons ml-4">edit</span>
            <span id="span_rc${id}" class="material-icons">remove_circle_outline</span>`
            // else
            //     item.innerHTML = `<span class="material-icons">place</span>${desc}`


            sideOptionsPins.appendChild(item)
        }
            
        }

        function getLines(){
            let item;
            @foreach($lines as $line)

            item = document.createElement('li');
            item.setAttribute('id', "spanl" + {{$line->code}})
            item.setAttribute('class', "list-group-item d-flex align-items-center pl-0 pr-0")

            item.innerHTML = `<span class="material-icons">place</span>{{$line->start}} - {{$line->end}}`

            sideOptions.appendChild(item)
            @endforeach
        }

        /*function makePath(id, t1, t2) {
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
        }*/
    </script>
    <div class="row d-flex flex-row-reverse">
        <div id="sideOptionsContainerPins" class="col-md-2 pr-0 mr-0 pl-0 ml-0" 
        @guest
        style="display: none;"
        @endguest
        >
            <ul id="sideOptionsPins" class="list-group"></ul>
        </div>
        @auth
            <div id="mapid" class="col-md-8 ml-0 pl-0 mr-0 pl-0"></div>
        @else
            <div id="mapid" class="col-md-10 ml-0 pl-0 mr-0 pl-0"></div>
        @endauth
        <script>
            "use strict";
            const API_Key = "pk.eyJ1Ijoic3BsaW10ZXIiLCJhIjoiY2s5dTgzeHkwMDByZTNocGc4aWcwMXMzbSJ9.4ncq--3gDyZIP1TbkNohqg";
            const mymap = L.map('mapid').setView([36.2238429, 1.2405443], 16.25);

            L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18, id: 'mapbox/streets-v11',
                tileSize: 512, zoomOffset: -1, accessToken: API_Key
            }).addTo(mymap);


            let mode = "", chk = 0, t1 = [], t2 = [];
            let desc, start, end;
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
                    mode = ""
                    setPins(e.latlng.lat, e.latlng.lng, desc, start, end)
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
                    <h5 class="modal-title" id="exampleModalLabel">X</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <label for="txt_desc">Nom</label>   
                        <input type="text" class="form-control" id="txt_desc" required>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button id="btn_submit" type="submit" class="btn btn-primary" disabled data-dismiss="modal">Ajouter</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <!-- Modal -->
    <div class="modal fade" id="LineModal" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ligne</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <div class="row text-center">
                <div class="col-3">
                <h5>Nomber de bus</h5>
                <h2>#</h1>
                <p id="num_bus"></p>
                </div>
                <div class="col-3">
                    <h5>Heure Début</h5>
                    <span class="material-icons mr-3">access_time</span>
                    <p>07:00</p>
                </div>
                <div class="col-3">
                    <h5>Heure Finie</h5>
                    <span class="material-icons mr-3">access_time</span>
                    <p>17:30</p>
                </div>
                <div class="col-3">
                <h5>Aller retoure</h5>
                <span class="material-icons mr-3">compare_arrows</span>
                </div>
                </div>
                <h3 class="text-center">Les Arret</h3>
                <ul id="assignedLines" class="list-group"></ul>
                </div>
                <div class="modal-footer">
                    <button id="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <script>
        initPins();
        //initPaths();
        getLines();

        $('li').on('click', (evnt) => {
            console.log("* ",evnt.target.id)
            objPins.map(e => {
                if (e.id == evnt.target.id.slice(4)) {
                    mymap.setView([e.lati, e.long], 16.25);
                }
            })
        });
        
    </script>
    <script src="{{ asset('js/handler.js') }}" type="application/javascript"></script>
@endsection
