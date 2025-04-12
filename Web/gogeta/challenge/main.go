package main

import (
	"html/template"
	"net/http"
	"os/exec"
	"regexp"
)

var tmpl = template.Must(template.ParseFiles("index.html"))

// Strict domain[:port]/path format
var validURL = regexp.MustCompile(`^[a-zA-Z0-9.-]+(:[0-9]+)?\/[a-zA-Z0-9/_\-\.]+$`)

func main() {
	http.HandleFunc("/", indexHandler)
	http.HandleFunc("/submit", submitHandler)

	http.ListenAndServe(":5000", nil)
}

func indexHandler(w http.ResponseWriter, r *http.Request) {
	tmpl.Execute(w, "")
}

func submitHandler(w http.ResponseWriter, r *http.Request) {
	r.ParseForm()
	url := r.FormValue("url")

	if !validURL.MatchString(url) {
		tmpl.Execute(w, "❌ Invalid format. Use domain.com[:port]/package")
		return
	}

	// No timeout, simple exec
	cmd := exec.Command("go", "get", "-insecure", url)
	cmdOutput, err := cmd.CombinedOutput()

	output := string(cmdOutput)
	if err != nil {
		output += "\n[!] Error: " + err.Error()
	}

	tmpl.Execute(w, output)
}