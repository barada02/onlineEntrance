// Questions for Math
const mathQuestions = [
    {
        question: "If 2x + 3 = 11, what is the value of x?",
        options: ["3", "4", "5", "6"],
        correct: 1
    },
    {
        question: "What is the area of a circle with radius 5 units?",
        options: ["25π", "10π", "15π", "20π"],
        correct: 0
    },
    {
        question: "Solve for y: 3y - 7 = 14",
        options: ["5", "7", "8", "9"],
        correct: 2
    },
    {
        question: "What is the square root of 144?",
        options: ["10", "11", "12", "13"],
        correct: 2
    }
];

// Questions for Computer Science
const csQuestions = [
    {
        question: "Which data structure uses LIFO?",
        options: ["Queue", "Stack", "Array", "Tree"],
        correct: 1
    },
    {
        question: "What does CPU stand for?",
        options: ["Central Processing Unit", "Central Program Utility", "Computer Personal Unit", "Central Protocol Unit"],
        correct: 0
    },
    {
        question: "Which is not a programming language?",
        options: ["Java", "Python", "HTML", "HTTP"],
        correct: 3
    },
    {
        question: "What is the binary of decimal 8?",
        options: ["1000", "1001", "1010", "1011"],
        correct: 0
    }
];

// Load questions
function loadQuestions(questions) {
    const container = document.getElementById('questionsContainer');
    if (!container || !questions) {
        console.error('Container or questions not found');
        return;
    }
    
    container.innerHTML = ''; // Clear existing questions
    questions.forEach((q, index) => {
        const questionHTML = `
            <div class="question-card">
                <h3>Question ${index + 1}</h3>
                <p>${q.question}</p>
                <div class="options">
                    ${q.options.map((option, i) => `
                        <div class="option">
                            <input type="radio" id="q${index}o${i}" name="q${index}" value="${i}">
                            <label for="q${index}o${i}">${option}</label>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
        container.innerHTML += questionHTML;
    });
}

// Timer functionality
function startTimer(duration) {
    let timer = duration;
    const timerDisplay = document.getElementById('timer');
    
    const countdown = setInterval(() => {
        const minutes = parseInt(timer / 60, 10);
        const seconds = parseInt(timer % 60, 10);

        timerDisplay.textContent = `Time: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

        if (--timer < 0) {
            clearInterval(countdown);
            submitExam(true);
        }
    }, 1000);
}

// Submit exam
function submitExam(timeUp = false) {
    if (timeUp) {
        document.getElementById('confirmModal').style.display = 'block';
        document.querySelector('.modal-content h2').textContent = "Time's Up!";
        document.querySelector('.modal-content p').textContent = "Your exam will be submitted automatically.";
        setTimeout(confirmSubmit, 2000);
    } else {
        document.getElementById('confirmModal').style.display = 'block';
    }
}

function closeModal() {
    document.getElementById('confirmModal').style.display = 'none';
}

function confirmSubmit() {
    const answers = [];
    questions.forEach((_, index) => {
        const selected = document.querySelector(`input[name="q${index}"]:checked`);
        answers.push(selected ? parseInt(selected.value) : -1);
    });

    // Calculate score
    let score = 0;
    answers.forEach((answer, index) => {
        if (answer === questions[index].correct) score++;
    });

    // Send to server
    const formData = new FormData();
    formData.append('answers', JSON.stringify(answers));
    formData.append('score', score);

    fetch('submit_exam.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'result.php';
        } else {
            alert('Error submitting exam. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error submitting exam. Please try again.');
    });
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', () => {
    // Get student's subject from PHP
    let questions;
    if (examSubject === "Math") {
        questions = mathQuestions;
    } else if (examSubject === "Computer Science") {
        questions = csQuestions;
    } else {
        console.error('Invalid exam subject');
        return;
    }
    
    // Load questions and start timer
    loadQuestions(questions);
    startTimer(600); // 10 minutes
});
