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
                    <button onclick="updateDriver({{$bus->id}},
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
                <button class="btn btn-success pb-0"
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
                    <input type="text" class="form-control" id="matricule" name="matricule"
                           placeholder="matricule de place..." required>
                    <label for="nbmr">Nombre de place assit</label>
                    <input type="text" class="form-control" id="nbmr" name="nmbrPlace" placeholder="nombre de place..."
                           required>
                    <label for="nbmrdebout">Nombre de place debout</label>
                    <input type="text" class="form-control" id="nbmrdebout" name="nmbrPlaceDebout"
                           placeholder="nombre de place..." required>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="btn_submit_bus" form="bus-form" class="btn btn-primary">Add</button>
            </div>
            <script>
                let submit = $("#btn_submit_bus")
                let iid = null

                submit.click(function (event) {
                    event.preventDefault();
                    let data = $('#bus-form').serializeArray()
                    data.shift()

                    var flated = {}
                    Object.keys({...data}).forEach(function(key) {
                        flated[Object.values({...data}[key])[0]] = Object.values({...data}[key])[1]
                    });

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (submit.text() === "Add")
                    $.ajax({
                        url: '/buses/',
                        type: 'POST',
                        data: flated
                    })
                    else if (submit.text() === "Update")
                        $.ajax({
                            url: '/buses/',
                            type: 'PUT' + iid,
                            data: flatted
                        })
                    iid = null

                    $("#matricule").val("")
                    $("#nmbrPlace").val("")
                    $("#nmbrPlaceDebout").val("")
                    submit.val("Add")

                    $("#busModal").modal('hide')
                    $('#bus_container').load(document.URL + ' #bus_container > *');
                })
                function deleteBus(id) {
                    $.ajax({
                        url: '/buses/' + id,
                        type: 'DELETE'
                    })
                }

                function updateDriver(id, data) {
                    $("#driverModal").modal('toggle')
                    $("#matricule").val(data[0])
                    $("#nmbrPlace").val(data[1])
                    $("#nmbrPlaceDebout").val(data[2])

                    submit.val("Update")
                    iid = id
                }
            </script>
            {{--// Example starter JavaScript for disabling form submissions if there are invalid fields--}}
            <script>
                (function () {
                    'use strict';
                    window.addEventListener('load', function () {
                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        var forms = document.getElementsByClassName('needs-validation');
                        // Loop over them and prevent submission
                        var validation = Array.prototype.filter.call(forms, function (form) {
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

