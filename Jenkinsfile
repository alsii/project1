pipeline {
    agent { docker { image 'composer' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
                sh 'composer up'
            }
        }
        stage('test') {
            steps {
                sh './vendor/bin/phpunit'
            }
        }
    }
}
