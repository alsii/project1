pipeline {
    agent { docker { image 'php' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
                sh 'composer up'
            }
        }
        stage('test') {
            steps {
                sh 'phpunit'
            }
        }
    }
}