pipeline {
    stages {
        stage {'Test All'}
        parallel {
            stage('test-5.6') {
                agent { docker { image 'php-composer:5.6' } }
                steps {
                    sh 'php --version'
                    sh 'composer up'
                    sh './vendor/bin/phpunit --log-junit build/reports/test.xml'
                }
            }
            stage('test-7.0') {
                agent { docker { image 'php-composer:7.0' } }
                steps {
                    sh 'php --version'
                    sh 'composer up'
                    sh './vendor/bin/phpunit --log-junit build/reports/test.xml'
                }
            }
            stage('test-7.1') {
                agent { docker { image 'php-composer:7.1' } }
                steps {
                    sh 'php --version'
                    sh 'composer up'
                    sh './vendor/bin/phpunit --log-junit build/reports/test.xml'
                }
            }
            stage('test-7.2') {
                agent { docker { image 'php-composer:7.2' } }
                steps {
                    sh 'php --version'
                    sh 'composer up'
                    sh './vendor/bin/phpunit --log-junit build/reports/test.xml'
                }
            }
        }
    }
    post {
        always {
            junit 'build/reports/*.xml'
        }
    }
}
