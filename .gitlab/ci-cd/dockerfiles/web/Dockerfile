#########
# Build and push this docker image to the gitlab docker registry when updated to ensure an accurate CI/CD pipeline
#
# docker build -t registry.gitlab.com/juicy-media-ltd/ukpid/ukpid-backend/web .gitlab/ci-cd/dockerfiles/web
# docker push registry.gitlab.com/juicy-media-ltd/ukpid/ukpid-backend/web
#########

FROM nginx:1-alpine

COPY templates /etc/nginx/templates
