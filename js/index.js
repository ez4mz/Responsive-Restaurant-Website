// prize application
function randArray() {
    let prizes = ["Peking Duck", "Kung Pao Chicken", "Hainanese Chicken Rice", "Shredded Pork with Garlic Sauce", "Century Egg and Pork Congee", "Truffle Mac n' Cheese", "Steak", "Arch Burger", "Roasted Salmon", "Surf n' Turf Fettuccine"];
    let prizeImages = [
        "images/pekingDuck.jpg",
        "images/kungPao.jpg",
        "images/hainaneseChicken.jpg",
        "images/shreddedPork.jpg",
        "images/congee.webp",
        "images/truffleMac.jpg",
        "images/steak.jpg",
        "images/burger.jpg",
        "images/salmon.jpeg",
        "images/cajunFettuccine.jpg",
    ];

    this.selectPrize = function() {
        let randomIndex = Math.floor(Math.random() * prizes.length);
        let selectedPrize = prizes[randomIndex];
        let selectedImage = prizeImages[randomIndex];

        let newWindow = window.open("", "Selected Prize " + Date.now(), "width=400, height=500");
        newWindow.document.write("<!DOCTYPE html>");
        newWindow.document.write("<html><head><title>AB | Selected Prize</title><link rel='stylesheet' href='../css/index_styles.css'>");
        newWindow.document.write("<style>");
        newWindow.document.write("body {text-align: center; }");
        newWindow.document.write("h1, p {color: white; }");
        newWindow.document.write("</style>");
        newWindow.document.write("</head><body>");
        newWindow.document.write("<h1>Enjoy your first meal, on the house!</h1>");
        newWindow.document.write("<p><b>" + selectedPrize + "</b></p>");
        newWindow.document.write("<img src='" + selectedImage + "' alt='" + selectedPrize + "' style='max-width: 300px;'>");
        newWindow.document.write("<p><a href='../index.html' id='return'> Click here to go back to home page </a></p>");
        newWindow.document.write("</body></html>");
        newWindow.document.close();
    };
}

// create a new instance of the randArray object and add an event listener to the button
let prizeApp = new randArray();
document.querySelector("button").addEventListener('click', prizeApp.selectPrize.bind(prizeApp));