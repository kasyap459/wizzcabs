                @foreach($datas as $index => $data)

                   <tr>
                        <td>{{ $index + 1 }}</td>
                        <td> {{$data->request_id}} </td>
                        <td> @if($data->provider != NULL){{$data->provider->name}}  @endif</td>
                        <td> @if($data->provider != NULL){{$data->provider->email}} @endif</td>
                        <td> @if($data->provider != NULL) {{$data->provider->mobile}} @endif </td>
                        <td> {{$data->created_at}} </td>
                        <td> ${{$data->amount}} </td>
                        <td>
                           
                            @if($data->status == 'REQUESTED')
                            <span class="tag tag-info pull-right" > {{$data->status}} </span><br>
                            <a href="#"  class="icondata" data-id="{{ $data->id }}" id="approvemodal"><i class="glyphicon glyphicon-ok"></i></a>
                            <a href="#" class="icondata " data-id="{{ $data->id }}" id="cancelmodal"><i class="fa fa-times"></i></a>

                             
                            @elseif($data->status == 'REJECTED')
                            <span class="tag tag-danger " > {{$data->status}} </span><br>
                            <a href="#"  class="icondata custom-class" data-id="" id=""><i class="glyphicon glyphicon-ok"></i></a>
                            <a href="#" class="icondata custom-class" data-id="" id=""><i class="fa fa-times"></i></a>

                            @elseif($data->status == 'APPROVED')
                            <span class="tag tag-success " > {{$data->status}} </span><br>
                            <a href="#"  class="icondata custom-class" data-id="" id=""><i class="glyphicon glyphicon-ok"></i></a>
                            <a href="#" class="icondata custom-class " data-id="" id=""><i class="fa fa-times"></i></a>
                            @endif
                        </td>
                    </tr>         
                    
                @endforeach
                
                
            


 