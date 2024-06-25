// Generate a random number between 0 and 100
const secretNumber = Math.floor(Math.random() * 101);
let attempts = 0;
const maxAttempts = 5;
let remain = maxAttempts - attempts;

function checkGuess() {
   
    const userGuess = parseInt(document.getElementById('guessInput').value);
    attempts++;
    remain = maxAttempts - attempts;

    // Check if the guess is correct
    if (userGuess === secretNumber) {
        document.getElementById('result').innerHTML = `Congratulations! You guessed the correct number in ${attempts} attempts.`;
        return;
    } else if (userGuess < secretNumber) {
         document.getElementById('result').innerHTML = `Too low! You have ${remain} ${remain === 1 ? 'chance' : 'chances'} left. Try again!`;
    } else {
        document.getElementById('result').innerHTML = `Too High! You have ${remain} ${remain === 1 ? 'chance' : 'chances'} left. Try again!`;
    }

     if (remain === 0) {
        document.getElementById('result').innerHTML = `Sorry, you've used all your attempts. The correct number was ${secretNumber}.`;
        return;
    }

}

