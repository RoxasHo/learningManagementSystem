<link rel="stylesheet" type="text/css" href="{{ asset('css/questionnaire.css') }}">
<x-layout>
    <div class="main-section" id="main-section">
        <div class="questionnaire-container">
            <h2 class="questionnaire-title">User Questionnaire</h2>
            <form method="POST" action="{{ route('questionnaire.store') }}">
                @csrf
                <!-- Education Background -->
                <label for="education_background">Education Background:</label>
                <select name="education_background" id="education_background" required>
                    <option value="" disabled selected>Select your education level</option>
                    <option value="primary">Primary</option>
                    <option value="secondary">Secondary</option>
                    <option value="tertiary">Tertiary</option>
                    <option value="graduated">Graduated</option>
                </select>

                <label for="programming_experience">Have you learned programming before?</label>
                <div class="questionnaire-question-radio">
                    <label for="programming_yes">
                        <input type="radio" name="programming_experience" id="programming_yes" value="1">
                        Yes
                    </label>
                    
                    <label for="programming_no">
                        <input type="radio" name="programming_experience" id="programming_no" value="0">
                        No
                    </label>
                </div>

                <label for="learned_languages">Languages Learned (if any):</label>
                <input type="text" name="learned_languages" id="learned_languages" placeholder="e.g. PHP, JavaScript">

                <!-- Interested Categories -->
                <label for="interested_categories">Courses Interested In:</label>
                <select name="interested_categories[]" id="interested_categories" multiple required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary">Submit</button>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-layout>


