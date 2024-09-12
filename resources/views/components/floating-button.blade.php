<div>
    <!-- Initially show the button -->
    <button id="floating-button" class="floating-button" onclick="toggleForm(true)">
        +
    </button>

    <!-- Initially hide the form -->
    <form id="floating-form" class="floating-form" method="POST" action="{{ route('add-course') }}" style="display: none;">
        @csrf
        <label>Course Name: </label>
        <input 
            class="CourseName" 
            type="text" 
            name="CourseName" 
            placeholder="Enter course name" 
            required 
        />
        <label>Difficulty: </label>
        <select 
            class="Difficulty" 
            name="Difficulty"
            required
        >
            <option value="Beginner">Beginner</option>
            <option value="Intermediate">Intermediate</option>
            <option value="Expert">Expert</option>
        </select>
        <br/>
        <label>Description: </label>
        <input 
            class="Description" 
            type="text" 
            name="Description" 
            placeholder="Enter description (max: 248)" 
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
