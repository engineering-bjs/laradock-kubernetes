{{/* vim: set filetype=mustache: */}}
{{/*
Expand the name of the chart.
*/}}
{{- define "laradock-kubernetes.name" -}}
{{- default .Chart.Name .Values.nameOverride | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{/*
Expand the laradock-kubernetes-workspace of the chart.
*/}}
{{- define "laradock-kubernetes.workspace" -}}
{{- default .Chart.Name .Values.workspacenameOverride | trunc 63 | trimSuffix "" -}}
{{- end -}}

{{/*
Expand the laradock-kubernetes-php-fpm of the chart.
*/}}
{{- define "laradock-kubernetes.phpfpm" -}}
{{- default .Chart.Name .Values.phpfpmnameOverride | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{/*
Expand the laradock-kubernetes-nginx of the chart.
*/}}
{{- define "laradock-kubernetes.nginx" -}}
{{- default .Chart.Name .Values.nginxnameOverride | trunc 63 | trimSuffix "-" -}}
{{- end -}}


{{/*
Expand the laradock-kubernetes-php-worker of the chart.
*/}}
{{- define "laradock-kubernetes.phpworker" -}}
{{- default .Chart.Name .Values.phpworkernameOverride | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{/*
Create a default fully qualified app name.
We truncate at 63 chars because some Kubernetes name fields are limited to this (by the DNS naming spec).
If release name contains chart name it will be used as a full name.
*/}}
{{- define "laradock-kubernetes.fullname" -}}
{{- if .Values.fullnameOverride -}}
{{- .Values.fullnameOverride | trunc 63 | trimSuffix "-" -}}
{{- else -}}
{{- $name := default .Chart.Name .Values.nameOverride -}}
{{- if contains $name .Release.Name -}}
{{- .Release.Name | trunc 63 | trimSuffix "-" -}}
{{- else -}}
{{- printf "%s-%s" .Release.Name $name | trunc 63 | trimSuffix "-" -}}
{{- end -}}
{{- end -}}
{{- end -}}

{{/*
Create chart name and version as used by the chart label.
*/}}
{{- define "laradock-kubernetes.chart" -}}
{{- printf "%s-%s" .Chart.Name .Chart.Version | replace "+" "_" | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{/*
Common labels
*/}}
{{- define "laradock-kubernetes.labels" -}}
app.kubernetes.io/name: {{ include "laradock-kubernetes.name" . }}
helm.sh/chart: {{ include "laradock-kubernetes.chart" . }}
app.kubernetes.io/instance: {{ .Release.Name }}
{{- if .Chart.AppVersion }}
app.kubernetes.io/version: {{ .Chart.AppVersion | quote }}
{{- end }}
app.kubernetes.io/managed-by: {{ .Release.Service }}
{{- end -}}

