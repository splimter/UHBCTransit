<div id="driver_container" class="row ml-3 mr-3 justify-content-center">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Nom</th>
            <th scope="col">Prenom</th>
            <th scope="col">Adress</th>
            <th scope="col">Experience</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($drivers as $driver)
            <tr>
                <th scope="row">1</th>
                <td>{{$driver->nom}}</td>
                <td>{{$driver->prenom}}</td>
                <td>{{$driver->adress}}</td>
                <td>{{$driver->exp}}</td>
                <td class="d-flex justify-content-center">
                    <button onclick="updateDriver({{$driver->id}},
                        [{{$driver->nom}}, {{$driver->prenom}}, {{$driver->adress}}, {{$driver->exp}}])"
                            class="btn btn-warning pb-0"
                            data-toggle="modal" data-target="#busModal"><span class="material-icons">create</span>
                    </button>
                    <button onclick="deleteDriver({{$driver->id}})" class="btn btn-danger pb-0">
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
            <td></td>
            <td class="d-flex justify-content-center">
                <button class="btn btn-success pb-0"
                        data-toggle="modal" data-target="#driverModal"><span class="material-icons">add_circle</span>
                </button>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="driverModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Driver</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="driver-form" action="{{route('drivers.store')}}" method="post" novalidate>
                    @csrf
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="nom..." required>
                    <label for="prenom">Prenom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="prenom..." required>
                    <label for="adress">Adress</label>
                    <input type="text" class="form-control" id="adress" name="adress" placeholder="adress..." required>
                    <label for="exp">Experience</label>
                    <input type="number" class="form-control" id="exp" name="exp" placeholder="experience..." required>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="btn_submit_driver" class="btn btn-primary">Add</button>
            </div>
            <script>
                let submit = $("#btn_submit_driver")
                let iid = null

                submit.click(function (event) {
                    event.preventDefault();

                    let data = $('#driver-form').serializeArray()
                    data.shift()

                    const flatted = {};
                    Object.keys({...data}).forEach(function (key) {
                        flatted[Object.values({...data}[key])[0]] = Object.values({...data}[key])[1]
                    });

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (submit.text() === "Add")
                        $.ajax({
                            url: '/drivers/',
                            type: 'POST',
                            data: flatted
                        })
                    else if (submit.text() === "Update")
                        $.ajax({
                            url: '/drivers/',
                            type: 'PUT' + iid,
                            data: flatted
                        })
                    iid = null

                    $("#nom").val("")
                    $("#prenom").val("")
                    $("#adress").val("")
                    $("#exp").val("")
                    submit.val("Add")

                    $("#driverModal").modal('hide')
                    $('#driver_container').load(document.URL + ' #driver_container > *');
                })

                function deleteDriver(id) {
                    $.ajax({
                        url: '/drivers/' + id,
                        type: 'DELETE'
                    })
                }

                function updateDriver(id, data) {
                    $("#driverModal").modal('toggle')
                    $("#nom").val(data[0])
                    $("#prenom").val(data[1])
                    $("#adress").val(data[2])
                    $("#exp").val(data[3])
                    submit.val("Update")
                    iid = id
                }
            </script>
        </div>
    </div>
</div>

