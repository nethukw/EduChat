<div class="container mt-5">
    <h3>Student Feedback</h3>
    <div class="row">
        @foreach($reviews as $review)
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $review->session_title }}</h5>
                    <h6 class="text-muted">By: {{ $review->student_name }}</h6>
                    <div class="text-warning mb-2">
                        @for($i=0; $i < $review->rating; $i++) ⭐ @endfor
                    </div>
                    <p class="card-text">"{{ $review->comment }}"</p>
                    <small class="text-muted">{{ $review->created_at }}</small>
                </div>
            </div>
        </div>
        @endforeach
        
        @if($reviews->isEmpty())
            <p>No reviews yet.</p>
        @endif
    </div>
</div>