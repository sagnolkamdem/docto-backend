// jenkins accept two syntax, groovy and shell.
pipeline {
    agent any

    stages {
        stage ('Init'){
            steps{
                echo 'Know the Jenkins server runtimme...'
                sh 'git --version'
            }
        }
        stage ('Checkout'){
            steps{
                echo 'Checkout...'
                checkout scm
            }
        }
        stage ('Install dependency'){
            steps{
                echo 'Installing dependecy...'
            }
        }
        stage('Code quality') {
            steps {
                echo 'Code quality coverage...'
            }
        }
        stage('Php unit') {
            steps {
                echo 'Unit testing...'
            }
        }
        stage('Features test') {
            steps {
                echo 'Features testing...'
                // Groovy syntax
                script {
                    def test = 2 + 2 > 3 ? 'cool' : 'not cool'
                    echo test
                }
            }
        }
        stage('Deploy to testing') {
            when { branch "develop" }
            environment {
                TESTING_SSH_USER = 'tabiblibops'
                TESTING_SERVER_IPADDRESS = '139.162.133.82'
                APP_DIR = '/var/www/tabiblib/api.test.tabiblib-services.com'
                APP_ENV_PATH = "${APP_DIR}/persistent/.env.test"
                RELEASES_DIR = "${APP_DIR}/code.tabiblib.backend.monolithique.api.v1.laravel"
            }
            steps {
                echo 'Deploying in testing with Docker compose...'
                sshagent(credentials: ['ssh-credentials-tabiblib-139.162.133.82-id']) {
                    sh '''
                        [ -d ~/.ssh ] || mkdir ~/.ssh && chmod 0700 ~/.ssh
                        ssh-keyscan -t rsa,dsa $TESTING_SERVER_IPADDRESS >> ~/.ssh/known_hosts

                        echo "pull last changes"
                        ssh $TESTING_SSH_USER@$TESTING_SERVER_IPADDRESS "cd $RELEASES_DIR && git pull origin develop"

                        echo "update docker-compose testing"
                        ssh $TESTING_SSH_USER@$TESTING_SERVER_IPADDRESS "cd $RELEASES_DIR && cp docker-compose.testing.yml docker-compose.yml"

                        echo "restart container"
                        ssh $TESTING_SSH_USER@$TESTING_SERVER_IPADDRESS "cd $RELEASES_DIR && docker compose --env-file $APP_ENV_PATH up -d"

                        echo "install dependecy..."
                        ssh $TESTING_SSH_USER@$TESTING_SERVER_IPADDRESS "cd $RELEASES_DIR && docker compose exec tabiblib-api-service composer install"

                        echo "run migration..."
                        ssh $TESTING_SSH_USER@$TESTING_SERVER_IPADDRESS "cd $RELEASES_DIR && docker compose exec tabiblib-api-service php artisan migrate"
                    '''
                }
                echo 'Deployment completed successfully'
            }
        }
        stage('Deploy to production') {
                    when { branch "main" }
                    environment {
                          SSH_USER = 'tabiblibapi'
                          SERVER_IPADDRESS = '172.104.143.189'
                          HOME_PATH = '/home/tabiblibapi'
                    }
                    steps {
                        echo 'Deploying in production....'
                        sh "cp EnvoyProduction.blade.php Envoy.blade.php"
                        sshagent(credentials: ['ssh-credentials-tabiblib-172.104.143.189-id']) {
                            sh '''
                                [ -d ~/.ssh ] || mkdir ~/.ssh && chmod 0700 ~/.ssh
                                ssh-keyscan -t rsa,dsa 172.104.143.189 >> ~/.ssh/known_hosts

                                echo "copy EnvoyProduction to Remote Server"
                                scp -P22 Envoy.blade.php $SSH_USER@$SERVER_IPADDRESS:$HOME_PATH

                                echo "install laravel/envoy"
                                ssh $SSH_USER@$SERVER_IPADDRESS "composer global require laravel/envoy"

                                echo "Start Deployment script"
                                ssh $SSH_USER@$SERVER_IPADDRESS "~/.config/composer/vendor/bin/envoy run deploy --commit=$GIT_COMMIT"

                                echo "Remove Envoy after successfully deployment"
                                ssh $SSH_USER@$SERVER_IPADDRESS "rm Envoy.blade.php"
                            '''
                        }
                        echo 'Deployment complete successfully'
                    }
        }
    }
}
