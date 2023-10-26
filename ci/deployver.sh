FRONTEND_VERSION=$(node -p -e "require('./package.json').version")
BACKEND_VERSION=$(node -p -e "require('./composer.json').version")
DATE_VERSION=$(date +%Y.%m.%d)
GITHUB_RUN_ATTEMPT= $(env | grep github.run_attempt | grep -oe '[^=]*$')
echo "${DATE_VERSION}-${GITHUB_RUN_ATTEMPT}"