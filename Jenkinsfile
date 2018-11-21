#!groovy
pipeline {
    agent any
    stages {
        stage('Build') {
	    steps {
	        sh 'git clone https://eDeploy:ehub1234@github.com/fuzzywy/Docker-Bird.git'
                sh 'chmod +x Docker-Bird/build/build-product.sh'
		sh 'Docker-Bird/build/build-product.sh'
	        sh 'rm -rf Docker-Bird'
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
    }
}
