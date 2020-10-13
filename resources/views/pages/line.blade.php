<div id="line_container" class="row ml-1 mr-1 justify-content-center">
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <!-- <th scope="col">Id</th> -->
            <th scope="col">Code</th>
            <th scope="col">Depart</th>
            <th scope="col">Arriver</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($lines as $line)
            <tr>
                <!-- <th scope="row">1</th> -->
                <td>{{$line->code}}</td>
                <td>{{$line->start}}</td>
                <td>{{$line->end}}</td>
                <td class="d-flex justify-content-around">
                    <button onclick="updateLine({{$line->id}},
                        ['{{$line->code}}', '{{$line->start}}', '{{$line->end}}'])"
                            class="btn btn-warning pb-0"><span class="material-icons">create</span>
                    </button>
                    <button onclick="deleteLine({{$line->id}})" class="btn btn-danger pb-0">
                        <span class="material-icons">remove_circle</span>
                    </button>
                </td>
            </tr>
        @endforeach
        <tr>
            <!-- <th scope="row"></th> -->
            <td></td>
            <td></td>
            <td></td>
            <td class="d-flex justify-content-center">
                <button class="btn btn-success pb-0" onclick="addLine()"
                        data-toggle="modal" data-target="#lineModal"><span class="material-icons">add_circle</span>
                </button>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="lineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ligne</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="line-form" action="{{route('lines.store')}}" method="post" novalidate>
                    @csrf
                    <label for="code">Code</label>
                    <input type="text" class="form-control" id="code" name="code" placeholder="code..." required>
                    <!-- <label for="start">Depart</label>
                    <input type="text" class="form-control" id="start" name="start" placeholder="depart..." required>
                    <label for="end">Arriver</label>
                    <input type="text" class="form-control" id="end" name="end" placeholder="arriver..." required> -->
                    <label for="exampleFormControlSelect1">Depart</label>
    <select class="form-control" id="start">
    @foreach($pins as $pin)
        <option>{{$pin->desc}}</option>
      @endforeach
    </select>
    <label for="exampleFormControlSelect1">Arriver</label>
    <select class="form-control" id="end">
    @foreach($pins as $pin)
        <option>{{$pin->desc}}</option>
      @endforeach
    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn_close" type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button id="btn_submit_line" class="btn btn-primary">Ajouter</button>
            </div>
            <script>
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                let submitl = $("#btn_submit_line")
                let iidl = null

                submitl.click(function (event) {
                    event.preventDefault();

                    // let data = $('#line-form').serializeArray()
                    // data.shift()

                    // const flatted = {};
                    // Object.keys({...data}).forEach(function (key) {
                    //     flatted[Object.values({...data}[key])[0]] = Object.values({...data}[key])[1]
                    // });

                    let data = {  
                            code: $("#code").val(),
                            start: $("#start").val(),
                            end: $("#end").val()
                        }


                    if (submitl.text() === "Ajouter")
                        $.ajax({
                            url: '/lines/',
                            type: 'POST',
                            data
                        }).done(function (data, textStatus, jqXHR) {
                            try {
                                $("#code").val("")    
                            } catch (error) {
                                console.log(error);
                            }
                            
                            reset()
                            $('#code_line_bus').load(document.URL + ' #code_line_bus > *');
                            $('#code_line_pb').load(document.URL + ' #code_line_pb > *');
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            alert(jqXHR.responseJSON.error)
                        })
                    else if (submitl.text() === "Modifier")
                        $.ajax({
                            url: '/lines/'+ iidl,
                            type: 'PUT' ,
                            data
                        }).done(function (data, textStatus, jqXHR) {
                            reset()
                            $('#busModal').load(document.URL + ' #busModal > *');
                            $('#pass_byModal').load(document.URL + ' #pass_byModal > *');
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            alert(jqXHR.responseJSON.error)
                        })

                    $("#lineModal").modal('hide')
                    $('#line_container').load(document.URL + ' #line_container > *');
                })

                function addLine() {
                    submitl.html('Ajouter');                    
                }

                function reset() {
                    $("#lineModal").modal('hide')
                    $('#line_container').load(document.URL + ' #line_container > *');

                    iidl = null
                    submitl.html("Ajouter")
                }

                function deleteLine(id) {
                    $.ajax({
                        url: '/lines/' + id,
                        type: 'DELETE'
                    })
                    $('#line_container').load(document.URL + ' #line_container > *');
                }

                function updateLine(id, data) {
                    $("#code").val(data[0])
                    $("#start").val(data[1])
                    $("#end").val(data[2])
                    submitl.html("Modifier")

                    $("#lineModal").modal('toggle')
                    iidl = id
                    $('#line_container').load(document.URL + ' #line_container > *');
                }
            </script>
        </div>
    </div>
</div>

