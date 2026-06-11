class ExamManager {
  constructor() {
    this.autoSaveDelay = 1500;
    this.syncInterval = 30000;
    this.autoSaveTimer = null;
    this.syncTimer = null;
    this.sessionData = null;
  }

  init(sessionData) {
    this.sessionData = sessionData;
    this.startAutoSave();
    this.startServerSync();
    this.syncLocalStorage();
  }

  startAutoSave() {
    console.log("Auto-save system initialized");
  }

  startServerSync() {
    console.log("Server sync initialized");
  }

  syncLocalStorage() {
    console.log("Syncing localStorage data");
  }

  saveAnswer(questionId, answer) {
    clearTimeout(this.autoSaveTimer);

    this.autoSaveTimer = setTimeout(() => {
      this.sendAnswerToServer(questionId, answer);
    }, this.autoSaveDelay);
  }

  sendAnswerToServer(questionId, answer) {
    const csrfName = document.querySelector('meta[name="csrf-name"]').content;
    const csrfHash = document.querySelector('meta[name="csrf-token"]').content;

    const formData = new FormData();
    formData.append("session_id", this.sessionData.sessionId);
    formData.append("question_id", questionId);
    formData.append("answer", answer);
    formData.append(csrfName, csrfHash);

    fetch("/exam/save-answer", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success && data.csrf_token) {
          document.querySelector('meta[name="csrf-token"]').content =
            data.csrf_token;
        }
      });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    document.querySelectorAll(".alert-dismissible").forEach((alert) => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    });
  }, 5000);
});
