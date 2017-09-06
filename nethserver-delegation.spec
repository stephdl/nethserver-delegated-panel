%define name nethserver-delegation

%define version 0.0.9
%define release 1
Summary: Delegate the usage of  panels to users or groups
Name: %{name}
Version: %{version}
Release: %{release}%{?dist}
Distribution: NethServer
License: GNU GPL version 2
Group: SMEserver/addon
Source: %{name}-%{version}.tar.gz
BuildArchitectures: noarch
BuildRoot: /var/tmp/%{name}-%{version}-buildroot
BuildRequires: nethserver-devtools
Requires: nethserver-directory
AutoReqProv: no

%description
Delegate the usage of  panels to users or groups

%prep
%setup
%build
%{makedocs}
perl createlinks

%install
rm -rf $RPM_BUILD_ROOT
(cd root ; find . -depth -print | cpio -dump $RPM_BUILD_ROOT)
rm -f %{name}-%{version}-filelist
/sbin/e-smith/genfilelist $RPM_BUILD_ROOT \
     > %{name}-%{version}-filelist
echo "%doc COPYING" >> %{name}-%{version}-filelist

%clean
rm -rf $RPM_BUILD_ROOT

%files -f %{name}-%{version}-filelist
%defattr(-,root,root)

%pre

%post


%changelog
* Wed Sep 6 2017 stephane de Labrusse <stephdl@de-labrusse.fr> 0.0.9-1
- Automatic activation of the shell access if the sudo power is enabled

* Mon Aug 7 2017 stephane de Labrusse <stephdl@de-labrusse.fr> 0.0.8-1
- NS7 version backported
- Sudo can be delegated
- Panel can be delegated per group or user

* Thu Jul 20 2017 stephane de Labrusse <stephdl@de-labrusse.fr> 0.0.7-1
- minor changes on variable initiations
- New UI (FIELDSET_EXPANDABLE)

* Wed Jul 12 2017 stephane de Labrusse <stephdl@de-labrusse.fr> 0.0.5-1
- start by denying access

* Wed Jun 28 2017 stephane de Labrusse <stephdl@de-labrusse.fr> 0.0.4-1
- Created a new specific file DelegatedPanel.json
- Added COPYING
- Created translation files for user and group plugin

* Sun Nov 11 2015 stephane de labrusse <stephdl@de-labrusse.fr> 0.0.3-1
- Added a plugin of delegation in User and Group panel

* Thu Nov 05 2015 stephane de labrusse <stephdl@de-labrusse.fr> 0.0.2-1
- corrected path to template

* Fri Oct 23 2015 stephane de labrusse <stephdl@de-labrusse.fr> 0.0.1-1
- First commit

