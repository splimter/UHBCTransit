<div id="bus_container" class="row ml-3 mr-3 justify-content-center">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Matricule</th>
            <th scope="col">nombre de place</th>
            <th scope="col">nombre de place debout</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($buses as $bus)
            <tr>
                <th scope="row">{{$bus->id}}</th>
                <td>{{$bus->matricule}}</td>
                <td>{{$bus->nmbrPlace}}</td>
                <td>{{$bus->nmbrPlaceDebout}}</td>
                <td class="d-flex justify-content-around">
                    <button onclick="updateBus({{$bus->id}},
                        [{{$bus->matricule}}, {{$bus->nmbrPlace}}, {{$bus->nmbrPlaceDebout}}])"
                            class="btn btn-warning pb-0"><span class="material-icons">create</span>
                    </button>
                    <button onclick="deleteBus({{$bus->id}})" class="btn btn-danger pb-0">
                        <span class="material-icons">remove_circle</span>
                    </button>
                </td>
            </tr>
        @endforeach
        <tr>
            <th scope="row"></th>
            <td></td>
            <td></td>
            <td></td>
            <td class="d-flex justify-content-center">
                <button class="btn btn-success pb-0" onclick="addBus()"
                        data-toggle="modal" data-target="#busModal"><span class="material-icons">add_circle</span>
                </button>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="busModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="bus-form" action="{{route('buses.store')}}" method="post" novalidate>
                    @csrf
                    <label for="matricule">Matricule</label>
                    <input type="number" class="form-control" id="matricule" name="matricule"
                           placeholder="matricule de place..." required>
                    <label for="nbmr">Nombre de place assit</label>
                    <input type="number" class="form-control" id="nbmr" name="nmbrPlace"
                           placeholder="nombre de place..."
                           required>
                    <label for="nbmrdebout">Nombre de place debout</label>
                    <input type="number" class="form-control" id="nbmrdebout" name="nmbrPlaceDebout"
                           placeholder="nombre de place..." required>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="btn_submit_bus" class="btn btn-primary">Add</button>
            </div>
            <script>
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let submitb = $("#btn_submit_bus")
                let iidb = null

                submitb.click(function (event) {
                    event.preventDefault();
                    let data = $('#bus-form').serializeArray()
                    data.shift()

                    var flatted = {}
                    Object.keys({...data}).forEach(function (key) {
                        flatted[Object.values({...data}[key])[0]] = Object.values({...data}[key])[1]
                    });

                    console.log(flatted)
                    if (submitb.text() === "Add")
                        $.ajax({
                            url: '/buses/',
                            type: 'POST',
                            data: flated
                        }).done(function (data, textStatus, jqXHR) {
                            $("#matricule").val("")
                            $("#nbmr").val("")
                            $("#nbmrdebout").val("")
                            reset()
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            alert(jqXHR.responseJSON.error)
                        })
                    else if (submitb.text() === "Update")
                        $.ajax({
                            url: '/buses/' + iidb,
                            type: 'PUT',
                            data: flatted
                        }).done(function (data, textStatus, jqXHR) {
                            reset()
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            alert(jqXHR.responseJSON.error)
                        })

                })

                function addBus() {
                    submitb.html('Add');
                }

                function reset() {
                    $("#busModal").modal('hide')
                    $('#bus_container').load(document.URL + ' #bus_container > *');
                    iidb = null
                    submitb.html('Add');
                }

                function deleteBus(id) {
                    $.ajax({
                        url: '/buses/' + id,
                        type: 'DELETE'
                    })
                    $('#bus_container').load(document.URL + ' #bus_container > *');
                }

                function updateBus(id, data) {
                    $("#matricule").val(data[0])
                    $("#nbmr").val(data[1])
                    $("#nbmrdebout").val(data[2])
                    submitb.html('Update');

                    $("#busModal").modal('toggle')

                    iidb = id
                    $('#bus_container').load(document.URL + ' #bus_container > *');
                }
            </script>
            {{--// Example starter JavaScript for disabling form submissions if there are invalid fields--}}
            <script>
                (function () {
                    'use strict';
                    window.addEventListener('load', function () {
                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        const forms = document.getElementsByClassName('needs-validation');
                        // Loop over them and prevent submission
                        const validation = Array.prototype.filter.call(forms, function (form) {
                            form.addEventListener('submit', function (event) {
                                if (form.checkValidity() === false) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                }
                                form.classList.add('was-validated');
                            }, false);
                        });
                    }, false);
                })();
            </script>
        </div>
    </div>
</div>

