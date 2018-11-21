#!groovy
pipeline {
    agent any
    stages {
        stage('Build') {
	    steps {
	        sh 'chmod +x build.sh'
		sh 'build.sh'
	    }
	}
	stage('Test') {
	    steps {
	        echo 'Testing...'
	    }
	}
	stage('Deploy'){
	    steps {
	        echo 'Deploying...'
	    }
	}
	stage('Clean'){
	    steps {
	        sh 'rm -rf Docker-Bird'
	    }
	}
    }
}
