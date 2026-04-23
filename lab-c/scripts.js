const map = L.map('map').setView([52.2297, 21.0122], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap'
}).addTo(map);

const locationBtn = document.getElementById("locationBtn");
const downloadBtn = document.getElementById("downloadBtn");
const canvasElement = document.getElementById("canvas");
const ctx = canvasElement.getContext("2d");

const puzzleContainer = document.getElementById("puzzle-container");
const board = document.getElementById("board");

const cells = document.querySelectorAll("#board div");
cells.forEach((cell, index) => {
  cell.dataset.index = index;
});

locationBtn.onclick = () => {
  if (!navigator.geolocation) return;

  navigator.geolocation.getCurrentPosition((position) => {
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;

    map.setView([lat, lng], 15);

    L.marker([lat, lng]).addTo(map)
      .bindPopup("You are here")
      .openPopup();
  });
};

downloadBtn.onclick = () => {
  leafletImage(map, function(err, canvas) {

    const width = board.clientWidth;
    const height = board.clientHeight;

    canvasElement.width = width;
    canvasElement.height = height;

    ctx.clearRect(0, 0, width, height);
    ctx.drawImage(canvas, 0, 0, width, height);

    createPuzzle(width, height);

    board.classList.add("active");
  });
};

function createPuzzle(width, height) {
  puzzleContainer.innerHTML = "";

  const pieceWidth = width / 4;
  const pieceHeight = height / 4;

  const image = canvasElement.toDataURL();

  let pieces = [];

  for (let y = 0; y < 4; y++) {
    for (let x = 0; x < 4; x++) {

      const piece = document.createElement("div");
      piece.className = "piece";

      piece.style.width = pieceWidth + "px";
      piece.style.height = pieceHeight + "px";

      piece.style.backgroundImage = `url(${image})`;
      piece.style.backgroundSize = `${width}px ${height}px`;
      piece.style.backgroundPosition = `-${x * pieceWidth}px -${y * pieceHeight}px`;
      piece.style.backgroundRepeat = "no-repeat";

      piece.id = "piece-" + (y * 4 + x);
      piece.draggable = true;

      piece.ondragstart = (e) => {
        e.dataTransfer.setData("text", piece.id);
      };

      pieces.push(piece);
    }
  }

  pieces.sort(() => Math.random() - 0.5);
  pieces.forEach(p => puzzleContainer.appendChild(p));

  initDragAndDrop();
}

function initDragAndDrop() {
  const cells = document.querySelectorAll("#board div");

  cells.forEach(cell => {

    cell.ondragover = (e) => e.preventDefault();

    cell.ondrop = (e) => {
      e.preventDefault();

      const draggedId = e.dataTransfer.getData("text");
      const dragged = document.getElementById(draggedId);

      if (!cell.firstElementChild) {
        cell.appendChild(dragged);
      } else {
        const existing = cell.firstElementChild;
        const parent = dragged.parentElement;

        cell.appendChild(dragged);
        parent.appendChild(existing);
      }

      setTimeout(() => {
        checkWin();
      }, 0);
    };
  });

  puzzleContainer.ondragover = (e) => e.preventDefault();

  puzzleContainer.ondrop = (e) => {
    e.preventDefault();

    const id = e.dataTransfer.getData("text");
    const el = document.getElementById(id);

    puzzleContainer.appendChild(el);
  };
}

function checkWin() {
  const cells = document.querySelectorAll("#board > div");

  for (let i = 0; i < cells.length; i++) {
    const piece = cells[i].firstElementChild;

    if (!piece) return;
    if (piece.id !== "piece-" + i) return;
  }

  if (Notification.permission === "granted") {
    new Notification("Puzzle completed correctly");
  } else if (Notification.permission !== "denied") {
    Notification.requestPermission().then(permission => {
      if (permission === "granted") {
        new Notification("Puzzle completed correctly");
      }
    });
  }

  console.log("Puzzle completed correctly");
}
