FRONTEND_VERSION=$(node -p -e "require('./package.json').version")
BACKEND_VERSION=$(node -p -e "require('./composer.json').version")
DATE_VERSION=$(date +%Y.%m.%d%H.%M)
echo $DATE_VERSION-$1
