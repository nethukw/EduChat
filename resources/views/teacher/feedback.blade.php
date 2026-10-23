<div class="container mt-5">
    <h3>Student Feedback</h3>
    <div class="row">
        {{-- This loop goes through every review object in the $reviews collection --}}
        @foreach($reviews as $review)
        <div class="col-md-6 mb-3">
            {{-- Using Bootstrap "Cards" to display each review separately --}}
            <div class="card">
                <div class="card-body">
                    {{-- Displaying the title of the specific session/class --}}
                    <h5 class="card-title">{{ $review->session_title }}</h5>

                    {{-- Showing the name of the student who wrote the review --}}
                    <h6 class="text-muted">By: {{ $review->student_name }}</h6>

                    {{-- STAR RATING LOGIC: --}}
                    {{-- This loop runs based on the 'rating' number (e.g., 5 stars) saved in the database --}}
                    <div class="text-warning mb-2">
                        @for($i=0; $i < $review->rating; $i++) ⭐ @endfor
                    </div>

                    {{-- The actual text feedback from the student --}}
                    <p class="card-text">"{{ $review->comment }}"</p>

                    {{-- Showing the date and time when the feedback was created --}}
                    <small class="text-muted">{{ $review->created_at }}</small>
                </div>
            </div>
        </div>
        @endforeach
        
        {{-- Empty State Check: If there are zero reviews, show this message --}}
        @if($reviews->isEmpty())
            <p>No reviews yet.</p>
        @endif
    </div>
</div>