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
                sh './vendor/bin/phpunit --log-junit build/reports/test.xml'
            }
        }
    }
    post {
        always {
            junit 'build/reports/*.xml'
        }
    }
}
