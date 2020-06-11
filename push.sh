  #!/bin/bash
git add .
echo "git add . finished"
read -p "Please input your git commit: " commit
echo "Your commit is '${commit}'"
git commit -m "${commit}"
echo "finished committing!"
git push heroku master
echo "Done!"