<div>
    <!-- Initially show the button -->
    <button id="floating-button" class="floating-button" onclick="toggleForm(true)">
        +
    </button>

    <!-- Initially hide the form -->
    <form id="floating-form" class="floating-form" method="POST" action="{{ route('add-question') }}" style="display: none;">
        @csrf

        <label>Question Number: </label>
        <input 
            class="question_number" 
            type="number" 
            name="question_number" 
            placeholder="Enter question number" 
            required 
        />

        <label>Statement: </label>
        <textarea 
            class="statement" 
            name="statement" 
            placeholder="Enter question statement" 
            required 
        ></textarea>

        <label>Option 1: </label>
        <input 
            class="option1" 
            type="text" 
            name="option1" 
            placeholder="Enter option 1" 
            required 
        />

        <label>Option 2: </label>
        <input 
            class="option2" 
            type="text" 
            name="option2" 
            placeholder="Enter option 2" 
            required 
        />

        <label>Option 3: </label>
        <input 
            class="option3" 
            type="text" 
            name="option3" 
            placeholder="Enter option 3" 
            required 
        />

        <label>Option 4: </label>
        <input 
            class="option4" 
            type="text" 
            name="option4" 
            placeholder="Enter option 4" 
            required 
        />

        <label>Option 5: </label>
        <input 
            class="option5" 
            type="text" 
            name="option5" 
            placeholder="Enter option 5" 
        />

        <label>Option 6: </label>
        <input 
            class="option6" 
            type="text" 
            name="option6" 
            placeholder="Enter option 6" 
        />

        <label>Type: </label>
        <select 
            class="type" 
            name="type" 
            required
        >
            <option value="Options">MCQ</option>
            <option value="Filled">Fill - in</option>
        </select>

        <label>Answer: </label>
        <input 
            class="answer" 
            type="text" 
            name="answer" 
            placeholder="Enter correct answer" 
            required 
        />

        <button class="close-button" type="button" onclick="toggleForm(false)">
            Close
        </button>
        <button type="submit">Submit</button>
    </form>
</div>

<script>
    function toggleForm(isVisible) {
        const form = document.getElementById('floating-form');
        const button = document.getElementById('floating-button');

        if (isVisible) {
            form.style.display = 'block';
            button.style.display = 'none';
        } else {
            form.style.display = 'none';
            button.style.display = 'block';
        }
    }
</script>
