@extends('instructor.instructor_dashboard')
@section('instructor')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">

    <div class="row">
        <div class="col-12">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset($course->feature_image) }}" class="p-1 border rounded-circle" width="90" height="90" alt="...">
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mt-0">{{ $course->course_name }}</h5>
                            <p class="mb-0">{{$course->course_title}}</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Topic</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Topic </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

            <form action="{{ route('add.topic') }}" method="POST">
                @csrf

                <input type="hidden" name="id" value="{{ $course->id }}">

                <div class="mb-3 form-group">
                    <label for="input1" class="form-label">Topic</label>
                    <input type="text" name="topic_title" class="form-control" id="input1"  >
                </div>

                <div class="mb-3 form-group">
                    <label for="input1" class="form-label">Summary</label>
                    <textarea name="topic_summary" class="form-control" id="input1"  ></textarea>
                </div>


                </div>
                <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>

            </div>
        </div>
    </div>
    <div class="row">
        @foreach ($topics as $key => $topic )
            <div class="col-lg-12">
                <div class="card">
                    <div class="p-4 card-body d-flex justify-content-between">
                        <div>
                            <h6>Topic {{ $key+1 }}: {{ $topic->topic_title }} </h6>
                            <p>{{ $topic->topic_summary }} </p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <form action="{{ route('delete.topic',$topic->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2 btn btn-danger ms-auto"> Delete Topic</button> &nbsp;
                            </form>
                            <a class="btn btn-primary" onclick="addLectureDiv({{ $course->id }}, {{ $topic->id }}, 'lectureContainer{{ $key }}' )" id="addLectureBtn($key)"> Add Lecture </a>
                        </div>
                    </div>

                    <div class="courseHide" id="lectureContainer{{ $key }}">
                        <div class="container">
                            @foreach ($topic->lectures as $lecture_key => $lecture)
                            <div class="px-3 mb-3 lectureDiv d-flex align-items-center justify-content-between">
                                <div>
                                    <strong> {{ $lecture_key+1 }}. {{ $lecture->lecture_title }}</strong>
                                </div>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary edit-lecture-btn" data-bs-toggle="modal" data-bs-target="#editLectureModal" data-lecture-id="{{ $lecture->id }}">Edit Lecture</button> &nbsp;
                                    <a href="{{ route('delete.lecture',$lecture->id) }}" class="btn btn-sm btn-danger" id="delete">Delete Lecture</a>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="editLectureModal" tabindex="-1" aria-labelledby="editLectureModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editLectureModalLabel">Edit Lecture </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                <form action="{{ route('update.lecture') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="id" value="{{ $course->id }}">
                                    <input type="hidden" name="lecture_id" id="lecture_id" value="">

                                    <div class="mb-3 form-group">
                                        <label for="lecture_title" class="form-label">Lecture</label>
                                        <input type="text" name="lecture_title" class="form-control" id="lecture_title" value=""  >
                                    </div>

                                    <div class="mb-3 form-group">
                                        <label for="video" class="form-label">Video</label>
                                        <input name="video" class="form-control" id="video" value=""  >
                                    </div>

                                    <div class="mb-3 form-group">
                                        <label for="url" class="form-label">Url</label>
                                        <input name="url" class="form-control" id="url" value=""  >
                                    </div>

                                    <div class="mb-3 form-group">
                                        <label for="content" class="form-label">Content</label>
                                        <input name="content" class="form-control" id="content" value=""  >
                                    </div>


                                    </div>
                                    <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Edit</button>
                                    </div>
                                </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

    <script>
        function addLectureDiv(courseId, topicId, containerId) {
            const lectureContainer = document.getElementById(containerId);//console.log(lectureContainer)
            const newLectureDiv = document.createElement('div');
            newLectureDiv.classList.add('lectureDiv','mb-3');
            newLectureDiv.innerHTML = `
            <div class="container">
                <h6>Lecture Title </h6>
                <input type="text" name="title" class="form-control" placeholder="Enter Lecture Title">
                <textarea class="mt-2 form-control" placeholder="Enter Lecture Content"  ></textarea>
                <h6 class="mt-3">Add Video Url</h6>
                <input type="text" name="lect_url" class="form-control" placeholder="Add URL">
                <button class="mt-3 btn btn-primary" onclick="saveLecture('${courseId}',${topicId},'${containerId}')" >Save Lecture</button>
                <button class="mt-3 btn btn-secondary" onclick="hideLectureContainer('${containerId}')">Cancel</button>
            </div>
                `;
            lectureContainer.appendChild(newLectureDiv);
        }

        function hideLectureContainer(containerId) {
            const lectureContainer = document.getElementById(containerId);
            lectureContainer.style.display = 'none';
            location.reload();
        }
    </script>

    <script>
        function saveLecture(courseId, topicId, containerId){
            const lectureContainer = document.getElementById(containerId);//console.log(lectureContainer)
            const lectureTitle = lectureContainer.querySelector('input[name="title"]').value;//console.log(lectureTitle)
            const lectureContent = lectureContainer.querySelector('textarea').value;//console.log(lectureContent)
            const lectureUrl = lectureContainer.querySelector('input[name="lect_url"]').value;//console.log(lectureUrl)
            fetch('/save-lecture', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    course_id: courseId,
                    topic_id: topicId,
                    lecture_title: lectureTitle,
                    lecture_url: lectureUrl,
                    content: lectureContent,
                }),
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);

                lectureContainer.style.display = 'none';
                location.reload();
                // Start Message
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 6000
                })
                if ($.isEmptyObject(data.error)) {

                        Toast.fire({
                        type: 'success',
                        title: data.success,
                        })
                }else{

                    Toast.fire({
                        type: 'error',
                        title: data.error,
                    })
                }
                // End Message

            })
            .catch(error => {
                console.error(error);
            });
        }
    </script>

    <script>
        $(document).ready(function () {
            $('.edit-lecture-btn').on('click', function () {
                // Get the lecture ID from data attribute
                var lectureId = $(this).data('lecture-id');console.log(lectureId)

                // Fetch lecture data using AJAX
                // $.ajax({
                //     url: '/get-lecture/' + lectureId,
                //     type: 'GET',
                //     success: function (lecture) {
                //         console.log('Success:', lecture);
                //         // Populate the modal with lecture data
                //         $('#lecture_title').val(lecture.lecture_title);
                //         $('#video').val(lecture.video);
                //         $('#url').val(lecture.url);
                //         $('#content').val(lecture.content);

                //         // Show the modal
                //         $('#editLectureModal').modal('show');
                //     },
                //     error: function (error) {
                //         console.error(error);
                //     }
                // });


                fetch(`/get-lecture/${lectureId}`)
                    .then(response => response.json())
                    .then(lecture => {
                        // Populate the form fields with lecture data
                        $('#lecture_id').val(lecture.id);
                        $('#lecture_title').val(lecture.lecture_title);
                        $('#video').val(lecture.video);
                        $('#url').val(lecture.url);
                        $('#content').val(lecture.content);

                        // Show the modal
                        $('#editLectureModal').modal('show');
                    })
                    .catch(error => console.error(error));
            });
        });
    </script>
@endsection
