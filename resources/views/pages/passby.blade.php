<script>
    const handleSHSection = (e) => { 
        if (e.currentTarget !== e.target) return; 
        const x = e.currentTarget.parentElement.lastElementChild; 
        if (x.style.display === "none") x.style.display = null; 
        else x.style.display = "none"; 
    };
</script>

<div id="pass_by_container" class="row ml-1 mr-1 justify-content-center">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th scope="col">Code de la ligne</th>
            <th scope="col">Nom d'arret</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($pass_bies as $pass_by)
            <tr>
                <td>{{$pass_by->code_line}}</td>
                <td>{{$pass_by->pin_name}}</td>
                <td class="d-flex justify-content-around">
                    <button onclick="updateDriver({{$pass_by->id}},
                        ['{{$pass_by->code_line}}', '{{$pass_by->pin_name}}'])"
                            class="btn btn-warning pb-0"><span class="material-icons">create</span>
                    </button>
                    <button onclick="deletePass({{$pass_by->id}})" class="btn btn-danger pb-0">
                        <span class="material-icons">remove_circle</span>
                    </button>
                </td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td></td>
            <td class="d-flex justify-content-center">
                <button class="btn btn-success pb-0" onclick="addPassBy()"
                        data-toggle="modal" data-target="#pass_byModal"><span class="material-icons">add_circle</span>
                </button>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="pass_byModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Passer Par</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="pass_by-form" action="{{route('pass_bies.store')}}" method="post" novalidate>
                    @csrf
                    <label for="code_line_pb">Code de la ligne</label>
                    <select class="form-control" id="code_line_pb">
                    @foreach($lines as $line)
                        <option>{{$line->code}}</option>
                    @endforeach
                    </select>
                    <label for="pin_name">Nom d'arret</label>
                    <!-- <input type="text" class="form-control" id="pin_name" name="pin_name" placeholder="Nom..." required> -->
                    <select class="form-control" id="pin_name">
                    @foreach($pins as $pin)
                        <option>{{$pin->desc}}</option>
                    @endforeach
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button id="btn_submit_pass_by" class="btn btn-primary">Ajouter</button>
            </div>
            <script>
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let submitpb = $("#btn_submit_pass_by")
                let iidpb = null

                submitpb.click(function (event) {
                    event.preventDefault();

                    /*let data = $('#pass_by-form').serializeArray()
                    data.shift()

                    const flatted = {};
                    Object.keys({...data}).forEach(function (key) {
                        flatted[Object.values({...data}[key])[0]] = Object.values({...data}[key])[1]
                    });*/

                    let data = {
                        code_line: $("#code_line_pb").val(),
                        pin_name: $("#pin_name").val(),
                    }

                    
                    if (submitpb.text() === "Ajouter")
                        $.ajax({
                            url: '/pass_bies/',
                            type: 'POST',
                            data
                        }).done(function (data, textStatus, jqXHR) {
                            reset()
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            alert(jqXHR.responseJSON.error)
                        })
                    else if (submitpb.text() === "Modifier")
                        $.ajax({
                            url: '/pass_bies/'+ iidpb,
                            type: 'PUT' ,
                            data
                        }).done(function (data, textStatus, jqXHR) {
                            reset()
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            alert(jqXHR.responseJSON.error)
                        })

                    $("#pass_byModal").modal('hide')
                    $('#pass_by_container').load(document.URL + ' #pass_by_container > *');
                })

                function addPassBy() {
                    submitpb.html('Ajouter');
                }

                function reset() {
                    $("#pass_byModal").modal('hide')
                    $('#pass_by_container').load(document.URL + ' #pass_by_container > *');

                    iidpb = null
                    submitpb.html("Ajouter")
                }

                function deletePass(id) {
                    $.ajax({
                        url: '/pass_bies/' + id,
                        type: 'DELETE'
                    })
                    $('#pass_by_container').load(document.URL + ' #pass_by_container > *');
                }

                function updateDriver(id, data) {
                    $("#code_line").val(data[0])
                    $("#pin_name").val(data[1])
                    submitpb.html("Modifier")

                    $("#pass_byModal").modal('toggle')
                    iidpb = id
                    $('#pass_by_container').load(document.URL + ' #pass_by_container > *');
                }
            </script>
        </div>
    </div>
</div>

