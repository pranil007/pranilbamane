document.addEventListener("DOMContentLoaded", function() {
    const questionContainer = document.getElementById('question-container');
    const questionText = document.getElementById('question-text');
    const optionsContainer = document.getElementById('options-container');
    const feedback = document.getElementById('feedback');
    const progress = document.getElementById('progress');
    const attemptedCount = document.getElementById('attempted-count');
    const totalCount = document.getElementById('total-count');
    const backBtn = document.getElementById('back-btn');
    const nextBtn = document.getElementById('next-btn');
    const resultContainer = document.getElementById('result-container');
    const resultMessage = document.getElementById('result-message');
    const scoreDisplay = document.getElementById('score');

    let currentQuestionIndex = 0;
    let score = 0;
    let totalQuestions = 5; // Number of questions to display
    let questions = [];
    let answers = [];

    // Fetch questions from XML file
    fetch('assessment.xml')
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const xml = parser.parseFromString(data, 'application/xml');
            const xmlQuestions = xml.querySelectorAll('question');
            
            // Convert XML questions to JavaScript objects
            xmlQuestions.forEach(q => {
                const question = {
                    text: q.querySelector('text').textContent,
                    options: Array.from(q.querySelectorAll('option')).map(opt => opt.textContent),
                    correctAnswer: q.querySelector('correctAnswer').textContent
                };
                questions.push(question);
            });

            // Shuffle questions array
            questions = shuffleArray(questions);

            // Display first set of questions
            displayQuestion(currentQuestionIndex);
        })
        .catch(error => console.error('Error fetching XML:', error));

    // Shuffle array function
    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    // Display current question
    function displayQuestion(index) {
        const currentQuestion = questions[index];
        questionText.textContent = currentQuestion.text;
        optionsContainer.innerHTML = '';

        currentQuestion.options.forEach(option => {
            const button = document.createElement('button');
            const nebutton = document.getElementById("next-btn");  //disable next btn for validation
            button.classList.add('optionbtns');
            nebutton.disabled = true;   //bydefault disable next btn for validation

            button.textContent = option;
            button.addEventListener('click', () => {
            checkAnswer(option, currentQuestion.correctAnswer);
            nebutton.disabled = !nebutton.disabled;   //change next btn state after click
            button.classList.toggle("active");          //added active class

            });
            optionsContainer.appendChild(button);
});

         progress.textContent = `Questions attempted: ${index}/${totalQuestions}`;
         attemptedCount.textContent = index;
         totalCount.textContent = totalQuestions;

        backBtn.disabled = index == 0;



    }





    // Check user's answer
    function checkAnswer(userAnswer, correctAnswer) {
        const isCorrect = userAnswer === correctAnswer;
        if (isCorrect) {
            feedback.textContent = "Correct!";
            feedback.style.color = 'green';     //ans color
            score += 2; // Marks per question
        } else {
            feedback.textContent = "Incorrect.";
            feedback.style.color = 'red';       //ans color
        }
         answers[currentQuestionIndex] = { question: questions[currentQuestionIndex].text, answer: userAnswer, correct: isCorrect };
    
    }



    // Move to next question or end assessment
    function nextQuestion() {
        currentQuestionIndex++;
        if (currentQuestionIndex < totalQuestions) {
            displayQuestion(currentQuestionIndex);
        } else {
            endAssessment();
        }
    }

    // Finish assessment and display results
    function endAssessment() {
        questionContainer.style.display = 'block';
        resultContainer.style.display = 'block';

        const resultText = score >= 6 ? "You've successfully passed the assessment!" : "You've failed the assessment.";
        resultMessage.textContent = resultText;
        scoreDisplay.textContent = `Score: ${score}/${totalQuestions * 2}`; // Total possible score

        // Display answers and marks
        const answersElement = document.createElement('div');
        answersElement.innerHTML = `<h3>Answers and Marks</h3>`;
        answers.forEach(answer => {
            const result = answer.correct ? `<span style="color: green;">Correct (+2)</span>` : `<span style="color: red;">Incorrect (0)</span>`;
            answersElement.innerHTML += `<p><strong>${answer.question}</strong>: ${answer.answer} - ${result}</p>`;
        });
        resultContainer.appendChild(answersElement);
    }

    // Event listeners for navigation buttons
     backBtn.addEventListener('click', () => {
         currentQuestionIndex -= 2; // Go back to previous question
         nextQuestion();
     });

    nextBtn.addEventListener('click', nextQuestion);
});

